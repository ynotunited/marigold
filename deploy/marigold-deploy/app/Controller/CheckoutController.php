<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Logger;
use App\Core\Model;
use App\Core\RowSecurity;
use App\Core\PaymentLedger;
use App\Service\PaymentService;
use App\Service\RateLimiter;
use App\Service\AuthService;
use App\Service\Settings;
use App\Service\NotificationService;

class CheckoutController extends Controller
{
    private const DEFAULT_VAT_RATE = 0.075;   // 7.5% VAT
    private const FLAT_SHIPPING = 15000.00;
    private const MAX_ITEMS = 50;

    /**
     * Current VAT rate (fraction) — configurable via the settings table.
     */
    private function vatRate(): float
    {
        $percent = Settings::getFloat('tax_rate', self::DEFAULT_VAT_RATE * 100);
        return round($percent / 100, 6);
    }

    public function index()
    {
        $products = Model::getDB()
            ->query("SELECT id, sku, name, slug, price, sale_price FROM products WHERE status = 'published' ORDER BY is_featured DESC, id ASC")
            ->fetchAll();

        return View::renderTemplate('pages/public/checkout', 'main', [
            'title' => 'Checkout | Marigold Signature',
            // Public key only — the secret key must NEVER reach the browser.
            'paystack_public_key' => $_ENV['PAYSTACK_PUBLIC_KEY'] ?? '',
            'products' => $products,
            'tax_rate' => $this->vatRate(),
            'csrf_token' => CSRF::field(),
        ]);
    }

    /**
     * Create an order (+ snapshot its addresses) and, for card payments,
     * initiate a payment intent (Paystack or Flutterwave). Priced
     * server-side from the DB — the client-supplied payload only selects
     * products and quantities.
     */
    public function submit()
    {
        $this->shield();

        $raw = file_get_contents('php://input');
        $data = json_decode((string) $raw, true);
        if (!is_array($data)) {
            $this->json(['error' => 'Invalid JSON body.'], 400);
        }

        // ---- Validate contact / shipping ----
        $email = isset($data['email']) ? filter_var(trim((string) $data['email']), FILTER_VALIDATE_EMAIL) : null;
        $first = trim((string) ($data['first_name'] ?? ''));
        $last = trim((string) ($data['last_name'] ?? ''));
        $phone = trim((string) ($data['phone'] ?? ''));
        if (!$email || $first === '' || mb_strlen($first) > 100 || $last === '' || mb_strlen($last) > 100) {
            $this->json(['error' => 'Valid first name, last name and email are required.'], 422);
        }

        // ---- Fulfilment: office pickup by default, delivery via ShipBubble courier ----
        $deliveryMethod = ($data['delivery_method'] ?? '') === 'delivery' ? 'delivery' : 'pickup';
        // The legacy /checkout page omits delivery_method but always submits an address.
        if (!isset($data['delivery_method']) && trim((string) ($data['address_line1'] ?? '')) !== '') {
            $deliveryMethod = 'delivery';
        }

        $whatsapp = preg_replace('/[^0-9+]/', '', trim((string) ($data['whatsapp'] ?? '')));

        // ---- ShipBubble courier selection (authoritative pricing) ----
        // The client only sends the quote token + courier identifiers. The fee is
        // re-read from the server-persisted quote, never trusted from the client.
        $shipping = 0.0;
        $shipbubbleMeta = null;
        if ($deliveryMethod === 'delivery') {
            $sbToken = trim((string) ($data['shipbubble_request_token'] ?? ''));
            $sbService = trim((string) ($data['shipbubble_service_code'] ?? ''));
            $sbCourier = trim((string) ($data['shipbubble_courier_id'] ?? ''));

            if ($sbToken !== '' && $sbService !== '' && $sbCourier !== '') {
                try {
                    $shipbubble = new \App\Service\ShipBubbleService();
                    $quote = $shipbubble->loadQuote($sbToken);
                    $courier = $quote ? $shipbubble->findCourier($quote, $sbService, $sbCourier) : null;
                } catch (\Throwable $t) {
                    \App\Core\Logger::error("Checkout quote lookup failed: {$t->getMessage()}", 'order');
                    $this->json(['error' => 'Delivery pricing is unavailable. Please try again.'], 502);
                }

                if (!$courier) {
                    $this->json(['error' => 'The delivery option you selected has expired. Please refresh the page and choose a courier again.'], 409);
                }

                $shipping = round((float) ($courier['total'] ?? 0), 2);
                $shipbubbleMeta = [
                    'request_token' => $sbToken,
                    'service_code' => $sbService,
                    'courier_id' => $sbCourier,
                    'courier_name' => (string) ($courier['courier_name'] ?? ''),
                ];
            } else {
                // Legacy WhatsApp-coordinated delivery (no ShipBubble selection).
                // Only enforced when delivery_method was explicitly requested;
                // the legacy fallback (address present, no delivery_method key)
                // keeps its original lenient behaviour.
                if (isset($data['delivery_method']) && $data['delivery_method'] === 'delivery') {
                    if ($whatsapp === '') {
                        $this->json(['error' => 'A valid WhatsApp number is required for delivery.'], 422);
                    }
                    $waDigits = preg_replace('/[^0-9]/', '', $whatsapp);
                    if ($waDigits === '' || strlen($waDigits) < 7 || strlen($waDigits) > 15) {
                        $this->json(['error' => 'A valid WhatsApp number is required for delivery.'], 422);
                    }
                }
            }
        }

        $address1 = trim((string) ($data['address_line1'] ?? ''));
        $city = trim((string) ($data['city'] ?? ''));
        if ($deliveryMethod === 'delivery' && ($address1 === '' || mb_strlen($address1) > 255 || $city === '' || mb_strlen($city) > 100)) {
            $this->json(['error' => 'Street address and city are required for delivery.'], 422);
        }

        $paymentMethod = (string) ($data['payment_method'] ?? 'transfer');
        $paystack = in_array($paymentMethod, ['paystack', 'card'], true);
        $flutterwave = in_array($paymentMethod, ['flutterwave', 'flw'], true);
        $paymentMethod = $paystack ? 'paystack' : ($flutterwave ? 'flutterwave' : 'transfer');
        $notes = trim((string) ($data['notes'] ?? ''));
        if (mb_strlen($notes) > 5000) {
            $this->json(['error' => 'Order notes are too long.'], 422);
        }

        // ---- Currency ----
        $currency = strtoupper(trim((string) ($data['currency'] ?? 'NGN')));
        if (!in_array($currency, \App\Core\Money::supportedCodes(), true)) {
            $currency = 'NGN';
        }
        // Store currency selection in session for persistence across requests
        Session::set('currency', $currency);

        // ---- Optional account creation at checkout (auto-login) ----
        $customerId = RowSecurity::customerId();
        $accountCreated = false;
        if (!empty($data['create_account'])) {
            $res = AuthService::registerFromCheckout([
                'first_name' => $first,
                'last_name' => $last,
                'email' => $email,
                'phone' => $phone,
                'password' => (string) ($data['password'] ?? ''),
            ]);
            if (isset($res['error'])) {
                $this->json(['error' => $res['error']], 422);
            }
            $accountCreated = true;
            $customerId = RowSecurity::customerId();
        }

        // ---- Validate items + price from the DB (catalogue fallback) ----
        $items = $data['items'] ?? [];
        if (!is_array($items) || count($items) === 0 || count($items) > self::MAX_ITEMS) {
            $this->json(['error' => 'Please add between 1 and ' . self::MAX_ITEMS . ' product(s).'], 422);
        }

        $priced = [];      // 'p{id}' for DB products, 's{slug}' for catalogue items
        $requested = [];   // extra payload metadata for non-DB items
        foreach ($items as $item) {
            if (!is_array($item)) {
                $this->json(['error' => 'Invalid item data.'], 422);
            }
            $qty = (int) ($item['quantity'] ?? 0);
            if ($qty < 1 || $qty > 1000000) {
                $this->json(['error' => 'Each item needs a quantity of at least 1.'], 422);
            }
            $productId = (int) ($item['product_id'] ?? 0);
            $slug = trim((string) ($item['slug'] ?? ''));
            if ($productId > 0) {
                $key = 'p' . $productId;
            } elseif ($slug !== '') {
                $key = 's' . $slug;
                $requested[$key] = [
                    'name' => mb_substr(trim((string) ($item['name'] ?? '')), 0, 255) ?: $slug,
                    'unit' => round((float) ($item['price'] ?? 0), 2),
                ];
            } else {
                $this->json(['error' => 'Each item needs a valid product.'], 422);
            }
            $priced[$key] = ($priced[$key] ?? 0) + $qty;
        }

        $productRows = $this->loadProducts(array_map(
            fn (string $k) => (int) substr($k, 1),
            array_filter(array_keys($priced), fn (string $k) => str_starts_with($k, 'p'))
        ));

        $slugToId = [];
        $slugKeys = array_filter(array_keys($priced), fn (string $k) => str_starts_with($k, 's'));
        if ($slugKeys) {
            $slugs = array_map(fn (string $k) => substr($k, 1), $slugKeys);
            $ph = implode(',', array_fill(0, count($slugs), '?'));
            $stmt = Model::getDB()->prepare(
                "SELECT id, slug, name, price, sale_price FROM products WHERE status = 'published' AND slug IN ($ph)"
            );
            $stmt->execute($slugs);
            foreach ($stmt->fetchAll() as $row) {
                $slugToId[$row['slug']] = $row;
            }
        }

        $orderItems = [];
        $subtotal = 0.0;
        foreach ($priced as $key => $qty) {
            if ($key[0] === 'p') {
                $row = $productRows[(int) substr($key, 1)] ?? null;
                if (!$row) {
                    $this->json(['error' => 'One or more products are unavailable.'], 422);
                }
                $unit = self::effectiveUnit($row);
                $name = $row['name'];
                $productId = (int) $row['id'];
            } else {
                $row = $slugToId[substr($key, 1)] ?? null;
                if ($row) {
                    $unit = self::effectiveUnit($row);
                    $name = $row['name'];
                    $productId = (int) $row['id'];
                } else {
                    $unit = $requested[$key]['unit'] ?? 0.0;
                    $name = $requested[$key]['name'] ?? substr($key, 1);
                    $productId = null;
                }
            }
            $line = round($unit * $qty, 2);
            $subtotal += $line;
            $orderItems[] = [
                'product_id' => $productId,
                'name' => $name,
                'quantity' => $qty,
                'price' => round($unit, 2),
                'subtotal' => $line,
            ];
        }

        $tax = round($subtotal * $this->vatRate(), 2);
        // Shipping is priced authoritatively from the persisted ShipBubble quote
        // (or 0.00 for pickup / legacy WhatsApp-coordinated delivery).
        $grandTotal = round($subtotal + $tax + $shipping, 2);

        // ---- Persist order + items + address snapshots ----
        $db = Model::getDB();
        $orderId = null;
        try {
            $db->beginTransaction();

            $orderNumber = $this->nextOrderNumber($db);
            $stmt = $db->prepare(
                "INSERT INTO orders
                    (order_number, customer_id, status, payment_status, delivery_method, whatsapp, subtotal, tax, shipping, grand_total, currency, payment_method, notes,
                     shipbubble_request_token, shipbubble_service_code, shipbubble_courier_id, shipbubble_courier_name)
                 VALUES
                    (:n, :c, 'pending', 'pending', :dm, :wa, :sub, :tax, :ship, :gt, :cur, :pm, :notes,
                     :sb_tok, :sb_svc, :sb_cou, :sb_name)"
            );
            $stmt->execute([
                'n' => $orderNumber,
                'c' => $customerId,
                'dm' => $deliveryMethod,
                'wa' => $deliveryMethod === 'delivery' ? $whatsapp : null,
                'sub' => $subtotal,
                'tax' => $tax,
                'ship' => $shipping,
                'gt' => $grandTotal,
                'cur' => $currency,
                'pm' => $paymentMethod,
                'notes' => $notes ?: null,
                'sb_tok' => $shipbubbleMeta['request_token'] ?? null,
                'sb_svc' => $shipbubbleMeta['service_code'] ?? null,
                'sb_cou' => $shipbubbleMeta['courier_id'] ?? null,
                'sb_name' => $shipbubbleMeta['courier_name'] ?? null,
            ]);
            $orderId = (int) $db->lastInsertId();

            $itemStmt = $db->prepare(
                "INSERT INTO order_items (order_id, product_id, name, quantity, price, subtotal)
                 VALUES (:o, :p, :name, :q, :pr, :s)"
            );
            foreach ($orderItems as $oi) {
                $itemStmt->execute([
                    'o' => $orderId,
                    'p' => $oi['product_id'],
                    'name' => $oi['name'] ?: null,
                    'q' => $oi['quantity'],
                    'pr' => $oi['price'],
                    's' => $oi['subtotal'],
                ]);
            }

            if ($deliveryMethod === 'delivery') {
                $shippingAddress = [
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => $email,
                    'phone' => $phone,
                    'company' => trim((string) ($data['company'] ?? '')),
                    'address_line1' => $address1,
                    'address_line2' => trim((string) ($data['address_line2'] ?? '')),
                    'city' => $city,
                    'state' => trim((string) ($data['state'] ?? '')),
                    'postal_code' => trim((string) ($data['postal_code'] ?? '')),
                    'country' => 'Nigeria',
                ];
            } else {
                $shippingAddress = [
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => $email,
                    'phone' => $phone,
                    'company' => trim((string) ($data['company'] ?? '')),
                    'address_line1' => '6 Oluwole Omole Street, Opebi',
                    'address_line2' => 'Office pickup',
                    'city' => 'Lagos',
                    'state' => 'Lagos',
                    'postal_code' => '',
                    'country' => 'Nigeria',
                ];
            }
            $this->insertAddress($db, $orderId, 'shipping', $shippingAddress);
            $this->insertAddress($db, $orderId, 'billing', $shippingAddress);

            $db->commit();
        } catch (\Throwable $t) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Logger::error('Checkout order persist failed: ' . $t->getMessage(), 'order');
            $this->json(['error' => 'We could not place your order. Please try again.'], 500);
        }

        $confirmationUrl = rtrim((string) ($_ENV['APP_URL'] ?? ''), '/');

        // ---- Initiate payment (card gateways only; transfers are handled offline) ----
        // Convert to target currency for the payment gateway
        $paymentAmountNgn = $grandTotal;
        if ($currency !== 'NGN') {
            $paymentAmountNgn = \App\Core\Money::convert($grandTotal, $currency);
        }
        $amountKobo = (int) round($paymentAmountNgn * 100);

        $intent = null;
        if ($paystack || $flutterwave) {
            try {
                $gatewayName = $flutterwave ? 'flutterwave' : 'paystack';
                $payments = new PaymentService($gatewayName);
                $out = $payments->createIntent(
                    [
                        'order_id' => $orderId,
                        'amount_kobo' => $amountKobo,
                        'currency' => 'NGN',
                        'customer_email' => $email,
                        'gateway' => $gatewayName,
                        'redirect_url' => $confirmationUrl . '/order-confirmation?method=' . $gatewayName,
                        'customer_name' => trim($first . ' ' . $last),
                        'customer_phone' => $phone,
                    ],
                    self::uuidV4(),
                    $this->requestMeta(),
                    RowSecurity::actor()
                );
                $intent = $out['result'];
            } catch (\Throwable $t) {
                Logger::error("Checkout intent creation failed for order #{$orderId}: {$t->getMessage()}", 'payment');
                $this->json(['error' => 'Payment could not be initiated. Please try again.'], 502);
            }

            if (!empty($intent['authorization_url'])) {
                $redirectUrl = $intent['authorization_url'];
            } else {
                $redirectUrl = $confirmationUrl . '/order-confirmation?ref=' . rawurlencode($intent['reference']) . '&method=' . $gatewayName;
            }
        } else {
            $redirectUrl = $confirmationUrl . '/order-confirmation?ref=' . rawurlencode($orderNumber) . '&method=transfer';
        }

        // ---- WhatsApp coordination link for deliveries ----
        $whatsappLink = null;
        if ($deliveryMethod === 'delivery' && $whatsapp !== '') {
            $waDigits = ltrim(preg_replace('/[^0-9]/', '', $whatsapp), '0');
            if (str_starts_with($waDigits, '234')) {
                $waDigits = '234' . ltrim(substr($waDigits, 3), '0');
            } elseif (strlen($waDigits) <= 10) {
                $waDigits = '234' . $waDigits;
            }
            $formattedTotal = \App\Core\Money::format($grandTotal, $currency);
            if ($shipbubbleMeta) {
                $message = "Hi {$first}! This is Marigold Signature. Your order {$orderNumber} (total "
                    . $formattedTotal . ") has been received. Delivery via {$shipbubbleMeta['courier_name']} — we'll confirm the schedule with you here on WhatsApp.";
            } else {
                $message = "Hi {$first}! This is Marigold Signature. Your order {$orderNumber} (total "
                    . $formattedTotal . ") has been received. You chose delivery — we'll confirm the delivery fee and schedule with you here on WhatsApp.";
            }
            $whatsappLink = 'https://wa.me/' . $waDigits . '?text=' . rawurlencode($message);
        }

        Logger::info("Order {$orderNumber} placed (total {$grandTotal} {$currency}, {$paymentMethod}, {$deliveryMethod})", 'order');

        if ($customerId) {
            $userId = NotificationService::userIdForOrder($customerId);
            if ($userId) {
                NotificationService::notify($userId, 'order', [
                    'icon' => 'package',
                    'title' => 'Order ' . $orderNumber . ' received',
                    'message' => 'Your order has been placed and is being processed. We will keep you updated on its status.',
                    'link' => '/account/orders/' . $orderNumber,
                ]);
            }
        }

        $this->json([
            'order_number' => $orderNumber,
            'order_id' => $orderId,
            'reference' => $intent['reference'] ?? null,
            'intent_id' => $intent['intent_id'] ?? null,
            'redirect_url' => $redirectUrl,
            'grand_total' => $grandTotal,
            'grand_total_formatted' => \App\Core\Money::format($grandTotal, $currency),
            'amount_kobo' => $amountKobo,
            'currency' => $currency,
            'delivery_method' => $deliveryMethod,
            'whatsapp' => $deliveryMethod === 'delivery' ? $whatsapp : null,
            'whatsapp_link' => $whatsappLink,
            'account_created' => $accountCreated,
        ], 201);
    }

    /**
     * Order confirmation — resolves a provider reference or order number and
     * renders the real status instead of a hardcoded success page.
     */
    public function confirmation()
    {
        // Paystack redirects back with `reference`/`trxref`, Flutterwave with
        // `tx_ref` — accept all three in addition to our own `ref`.
        $ref = trim((string) ($_GET['ref'] ?? $_GET['tx_ref'] ?? $_GET['reference'] ?? $_GET['trxref'] ?? ''));
        $method = ($_GET['method'] ?? '') === 'transfer' ? 'transfer' : 'paystack';

        $data = [
            'ref' => htmlspecialchars($ref, ENT_QUOTES, 'UTF-8'),
            'method' => $method,
            'order' => null,
            'status_label' => null,
            'amount' => null,
            'date' => null,
            'customer_email' => null,
            'found' => false,
        ];

        if ($ref !== '') {
            $db = Model::getDB();

            $intent = null;
            $stmt = $db->prepare("SELECT * FROM payment_intents WHERE gateway_ref = :r LIMIT 1");
            $stmt->execute(['r' => $ref]);
            $row = $stmt->fetch();
            if ($row) {
                $intent = $row;
            }

            $order = null;
            $statusLabel = null;
            if ($intent) {
                $gateway = (string) ($intent['gateway'] ?? $method);
                $ledgerStatus = PaymentLedger::currentStatus((int) $intent['id']);
                if (!in_array($ledgerStatus, ['captured', 'failed', 'refunded'], true)) {
                    try {
                        $payments = new PaymentService($gateway);
                        $payments->capture(
                            (int) $intent['id'],
                            [],
                            self::uuidFromSeed('checkout_return_' . (string) $intent['gateway_ref']),
                            $this->requestMeta(),
                            RowSecurity::actor()
                        );
                    } catch (\Throwable $t) {
                        Logger::warning("Checkout return reconcile failed for intent #{$intent['id']}: {$t->getMessage()}", 'payment');
                    }
                }

                $stmt = $db->prepare("SELECT * FROM orders WHERE id = :i LIMIT 1");
                $stmt->execute(['i' => (int) $intent['order_id']]);
                $order = $stmt->fetch() ?: null;
                $ledgerStatus = PaymentLedger::currentStatus((int) $intent['id']);
                $statusLabel = $this->labelFromLedger($ledgerStatus);
                $data['customer_email'] = $intent['customer_email'];
                $data['found'] = true;
            } else {
                $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = :n LIMIT 1");
                $stmt->execute(['n' => $ref]);
                $order = $stmt->fetch() ?: null;
                if ($order) {
                    $statusLabel = $this->labelFromPaymentStatus((string) $order['payment_status']);
                    $data['found'] = true;
                }
            }

            if ($order) {
                $data['order'] = $order;
                // Reflect the actual gateway the order was paid through.
                $data['method'] = (string) ($order['payment_method'] ?? $method);
                $data['status_label'] = $statusLabel;
                $orderCurrency = (string) ($order['currency'] ?? 'NGN');
                $data['amount'] = \App\Core\Money::format((float) $order['grand_total'], $orderCurrency);
                $data['amount_raw'] = (float) $order['grand_total'];
                $data['currency'] = $orderCurrency;
                $data['date'] = date('F j, Y', strtotime((string) $order['created_at']));
            }
        }

        return View::renderTemplate('pages/public/order_confirmation', 'main', [
            'title' => 'Order Confirmation | Marigold Signature',
            'order_page' => $data,
        ]);
    }

    // ------------------------------------------------------------------ utils

    private function labelFromLedger(?string $status): string
    {
        return match ($status) {
            'captured', 'authorized' => 'Payment Successful!',
            'failed' => 'Payment Failed',
            'refunded' => 'Payment Refunded',
            default => 'Payment Pending',
        };
    }

    private function labelFromPaymentStatus(string $status): string
    {
        return match ($status) {
            'paid' => 'Payment Successful!',
            'failed' => 'Payment Failed',
            'refunded' => 'Payment Refunded',
            default => 'Payment Pending',
        };
    }

    private function loadProducts(array $ids): array
    {
        if (!$ids) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Model::getDB()->prepare(
            "SELECT id, sku, name, price, sale_price FROM products
             WHERE status = 'published' AND id IN ($placeholders)"
        );
        $stmt->execute($ids);
        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[(int) $row['id']] = $row;
        }
        return $map;
    }

    private static function effectiveUnit(array $row): float
    {
        return $row['sale_price'] !== null && (float) $row['sale_price'] < (float) $row['price']
            ? (float) $row['sale_price']
            : (float) $row['price'];
    }

    private function insertAddress(\PDO $db, int $orderId, string $type, array $a): void
    {
        $stmt = $db->prepare(
            "INSERT INTO order_addresses
                (order_id, type, first_name, last_name, email, phone, company, address_line1, address_line2, city, state, postal_code, country)
             VALUES
                (:o, :t, :fn, :ln, :em, :ph, :co, :a1, :a2, :ci, :st, :pc, :cu)"
        );
        $stmt->execute([
            'o' => $orderId,
            't' => $type,
            'fn' => mb_substr(trim((string) ($a['first_name'] ?? '')), 0, 100),
            'ln' => mb_substr(trim((string) ($a['last_name'] ?? '')), 0, 100),
            'em' => $a['email'] ?? null,
            'ph' => !empty($a['phone']) ? mb_substr(trim((string) $a['phone']), 0, 30) : null,
            'co' => !empty($a['company']) ? mb_substr(trim((string) $a['company']), 0, 150) : null,
            'a1' => mb_substr(trim((string) ($a['address_line1'] ?? '')), 0, 255),
            'a2' => !empty($a['address_line2']) ? mb_substr(trim((string) $a['address_line2']), 0, 255) : null,
            'ci' => mb_substr(trim((string) ($a['city'] ?? '')), 0, 100),
            'st' => !empty($a['state']) ? mb_substr(trim((string) $a['state']), 0, 100) : null,
            'pc' => !empty($a['postal_code']) ? mb_substr(trim((string) $a['postal_code']), 0, 20) : null,
            'cu' => mb_substr(trim((string) ($a['country'] ?? 'Nigeria')), 0, 100),
        ]);
    }

    private function nextOrderNumber(\PDO $db): string
    {
        for ($i = 0; $i < 5; $i++) {
            $num = 'MS-O-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            $stmt = $db->prepare("SELECT COUNT(*) FROM orders WHERE order_number = :n");
            $stmt->execute(['n' => $num]);
            if ((int) $stmt->fetchColumn() === 0) {
                return $num;
            }
        }
        throw new \RuntimeException('Could not allocate a unique order number.');
    }

    private static function uuidV4(): string
    {
        $b = random_bytes(16);
        $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
        $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }

    /**
     * Deterministic UUID v4 derived from a seed string. Guarantees the same
     * reference always maps to the same idempotency key so re-visits to the
     * confirmation page replay the original capture instead of re-verifying.
     */
    private static function uuidFromSeed(string $seed): string
    {
        $b = hex2bin(hash('sha256', $seed));
        $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
        $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }

    private function shield(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed.'], 405);
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $limitKey = 'checkout_' . hash('sha256', $ip);
        if (RateLimiter::tooManyAttempts($limitKey, 10)) {
            Logger::warning("Checkout rate-limit hit. IP: {$ip}", 'auth');
            $this->json(['error' => 'Too many requests. Please try again later.'], 429);
        }
        RateLimiter::hit($limitKey, 60);

        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!CSRF::verify($token)) {
            $this->json(['error' => 'Invalid CSRF token.'], 401);
        }
    }

    private function requestMeta(): array
    {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            'session' => Session::get('user_id') ? 'user_' . Session::get('user_id') : 'guest',
        ];
    }
}
