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

class CheckoutController extends Controller
{
    private const VAT_RATE = 0.075;   // 7.5% VAT
    private const FLAT_SHIPPING = 15000.00;
    private const MAX_ITEMS = 50;

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
            'csrf_token' => CSRF::field(),
        ]);
    }

    /**
     * Create an order (+ snapshot its addresses) and, for card payments,
     * initiate a Paystack intent. Priced server-side from the DB — the
     * client-supplied payload only selects products and quantities.
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
        if (!$email || $first === '' || mb_strlen($first) > 100 || $last === '' || mb_strlen($last) > 100) {
            $this->json(['error' => 'Valid first name, last name and email are required.'], 422);
        }

        $address1 = trim((string) ($data['address_line1'] ?? ''));
        $city = trim((string) ($data['city'] ?? ''));
        if ($address1 === '' || mb_strlen($address1) > 255 || $city === '' || mb_strlen($city) > 100) {
            $this->json(['error' => 'Street address and city are required.'], 422);
        }

        $paymentMethod = in_array($data['payment_method'] ?? '', ['paystack', 'transfer'], true)
            ? (string) $data['payment_method']
            : 'paystack';
        $notes = trim((string) ($data['notes'] ?? ''));
        if (mb_strlen($notes) > 5000) {
            $this->json(['error' => 'Order notes are too long.'], 422);
        }

        // ---- Validate items + price from the DB ----
        $items = $data['items'] ?? [];
        if (!is_array($items) || count($items) === 0 || count($items) > self::MAX_ITEMS) {
            $this->json(['error' => 'Please add between 1 and ' . self::MAX_ITEMS . ' product(s).'], 422);
        }

        $priced = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                $this->json(['error' => 'Invalid item data.'], 422);
            }
            $productId = (int) ($item['product_id'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 0);
            if ($productId <= 0 || $qty < 1 || $qty > 1000000) {
                $this->json(['error' => 'Each item needs a valid product and quantity of at least 1.'], 422);
            }
            $priced[$productId] = ($priced[$productId] ?? 0) + $qty;
        }

        $productRows = $this->loadProducts(array_keys($priced));
        $orderItems = [];
        $subtotal = 0.0;
        foreach ($priced as $productId => $qty) {
            if (!isset($productRows[$productId])) {
                $this->json(['error' => 'One or more products are unavailable.'], 422);
            }
            $row = $productRows[$productId];
            $unit = $row['sale_price'] !== null && (float) $row['sale_price'] < (float) $row['price']
                ? (float) $row['sale_price']
                : (float) $row['price'];
            $line = round($unit * $qty, 2);
            $subtotal += $line;
            $orderItems[] = [
                'product_id' => $productId,
                'quantity' => $qty,
                'price' => round($unit, 2),
                'subtotal' => $line,
            ];
        }

        $tax = round($subtotal * self::VAT_RATE, 2);
        $shipping = $subtotal > 0 ? self::FLAT_SHIPPING : 0.0;
        $grandTotal = round($subtotal + $tax + $shipping, 2);
        $amountKobo = (int) round($grandTotal * 100);

        // ---- Persist order + items + address snapshots ----
        $db = Model::getDB();
        $orderId = null;
        try {
            $db->beginTransaction();

            $orderNumber = $this->nextOrderNumber($db);
            $stmt = $db->prepare(
                "INSERT INTO orders
                    (order_number, customer_id, status, payment_status, subtotal, tax, shipping, grand_total, payment_method, notes)
                 VALUES
                    (:n, :c, 'pending', 'pending', :sub, :tax, :ship, :gt, :pm, :notes)"
            );
            $stmt->execute([
                'n' => $orderNumber,
                'c' => RowSecurity::customerId(),
                'sub' => $subtotal,
                'tax' => $tax,
                'ship' => $shipping,
                'gt' => $grandTotal,
                'pm' => $paymentMethod === 'paystack' ? 'paystack' : 'bank_transfer',
                'notes' => $notes ?: null,
            ]);
            $orderId = (int) $db->lastInsertId();

            $itemStmt = $db->prepare(
                "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
                 VALUES (:o, :p, :q, :pr, :s)"
            );
            foreach ($orderItems as $oi) {
                $itemStmt->execute([
                    'o' => $orderId,
                    'p' => $oi['product_id'],
                    'q' => $oi['quantity'],
                    'pr' => $oi['price'],
                    's' => $oi['subtotal'],
                ]);
            }

            $this->insertAddress($db, $orderId, 'shipping', $data);
            $billing = [
                'first_name' => $data['billing_first_name'] ?? $first,
                'last_name' => $data['billing_last_name'] ?? $last,
                'address_line1' => $data['billing_address_line1'] ?? $address1,
                'city' => $data['billing_city'] ?? $city,
                'address_line2' => $data['billing_address_line2'] ?? ($data['address_line2'] ?? ''),
                'state' => $data['billing_state'] ?? ($data['state'] ?? ''),
                'postal_code' => $data['billing_postal_code'] ?? ($data['postal_code'] ?? ''),
            ];
            $this->insertAddress($db, $orderId, 'billing', $billing);

            $db->commit();
        } catch (\Throwable $t) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Logger::error('Checkout order persist failed: ' . $t->getMessage(), 'order');
            $this->json(['error' => 'We could not place your order. Please try again.'], 500);
        }

        $confirmationUrl = rtrim((string) ($_ENV['APP_URL'] ?? ''), '/');

        // ---- Initiate payment (card only; transfers are handled offline) ----
        $intent = null;
        if ($paymentMethod === 'paystack') {
            try {
                $payments = new PaymentService();
                $out = $payments->createIntent(
                    [
                        'order_id' => $orderId,
                        'amount_kobo' => $amountKobo,
                        'currency' => 'NGN',
                        'customer_email' => $email,
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
                $redirectUrl = $confirmationUrl . '/order-confirmation?ref=' . rawurlencode($intent['reference']) . '&method=paystack';
            }
        } else {
            $redirectUrl = $confirmationUrl . '/order-confirmation?ref=' . rawurlencode($orderNumber) . '&method=transfer';
        }

        Logger::info("Order {$orderNumber} placed (total {$grandTotal} NGN, {$paymentMethod})", 'order');

        $this->json([
            'order_number' => $orderNumber,
            'order_id' => $orderId,
            'reference' => $intent['reference'] ?? null,
            'intent_id' => $intent['intent_id'] ?? null,
            'redirect_url' => $redirectUrl,
            'grand_total' => $grandTotal,
            'amount_kobo' => $amountKobo,
            'currency' => 'NGN',
        ], 201);
    }

    /**
     * Order confirmation — resolves a provider reference or order number and
     * renders the real status instead of a hardcoded success page.
     */
    public function confirmation()
    {
        $ref = trim((string) ($_GET['ref'] ?? ''));
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
                $data['status_label'] = $statusLabel;
                $data['amount'] = number_format((float) $order['grand_total'], 2);
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
