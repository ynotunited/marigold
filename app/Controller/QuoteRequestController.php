<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Logger;
use App\Core\Validator;
use App\Core\Model;
use App\Core\RowSecurity;
use App\Service\RateLimiter;
use App\Service\Mailer;

class QuoteRequestController extends Controller
{
    private const MAX_ITEMS = 20;
    private const MAX_UPLOADS = 5;
    private const MAX_UPLOAD_SIZE = 20971520; // 20MB
    private const ALLOWED_UPLOAD_EXTENSIONS = ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'ai', 'eps', 'svg'];

    private const PRODUCT_IMAGES = [
        'MS-EXEC-001' => 'https://images.unsplash.com/photo-1544816278-ca5e3f4abd8c?q=80&w=200&auto=format&fit=crop',
        'MS-FLASK-001' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=200&auto=format&fit=crop',
        'MS-PEN-003' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=200&auto=format&fit=crop',
        'MS-TECH-004' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=200&auto=format&fit=crop',
    ];

    /**
     * Show the multi-product quote basket form
     */
    public function index()
    {
        // Pre-populate if a product_id was passed (from product/shop page)
        $product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;

        $preSelected = null;
        if ($product_id > 0) {
            $stmt = Model::getDB()->prepare(
                "SELECT id, name, sku FROM products WHERE id = :id AND status = 'published' LIMIT 1"
            );
            $stmt->execute(['id' => $product_id]);
            $row = $stmt->fetch();
            if ($row) {
                $sku = (string) $row['sku'];
                $preSelected = [
                    'id' => (int) $row['id'],
                    'name' => $row['name'],
                    'sku' => $sku,
                    'image' => self::PRODUCT_IMAGES[$sku] ?? '',
                ];
            }
        }

        return View::renderTemplate('pages/public/quote_request', 'main', [
            'title' => 'Request a Quote | Marigold Signature',
            'meta_description' => 'Request a custom corporate merchandise quote from Marigold Signature. Bulk pricing, branded items, and bespoke gifting solutions for your business.',
            'preSelected' => $preSelected,
            'csrf_token' => CSRF::field(),
        ]);
    }

    /**
     * Handle quote form submission
     */
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/quote-request');
        }

        // CSRF protection
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        // Honeypot: bots fill hidden fields; humans never do
        if (!empty(trim((string) ($_POST['website'] ?? '')))) {
            Logger::warning('Quote request honeypot triggered. IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''), 'http');
            $this->redirect('/quote-request/success');
        }

        // Per-IP rate limit to prevent scraping / spam
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $rateLimitKey = 'quote_request_' . hash('sha256', $ip);
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            Logger::warning("Quote request rate-limit hit. IP: $ip", 'auth');
            Session::set('error', 'Too many quote requests. Please try again later.');
            $this->redirect('/quote-request');
        }
        RateLimiter::hit($rateLimitKey, 3600);

        // Validate contact details
        $validator = new Validator();
        if (!$validator->validate($_POST, [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'company' => 'max:150',
            'email' => 'required|email',
            'phone' => 'max:30',
            'notes' => 'max:5000',
        ])) {
            Session::set('errors', $validator->getErrors());
            $this->redirect('/quote-request');
        }

        // Validate product items
        $items = $_POST['items'] ?? [];
        if (!is_array($items) || count($items) === 0 || count($items) > self::MAX_ITEMS) {
            Session::set('errors', ['items' => ['Please add between 1 and ' . self::MAX_ITEMS . ' product(s).']]);
            $this->redirect('/quote-request');
        }

        foreach ($items as $item) {
            if (!is_array($item)) {
                $this->redirect('/quote-request');
            }
            $name = trim((string) ($item['name'] ?? ''));
            $qty = (int) ($item['quantity'] ?? 0);
            $note = trim((string) ($item['notes'] ?? ''));
            if ($name === '' || strlen($name) > 200 || $qty < 1 || $qty > 1000000 || strlen($note) > 1000) {
                Session::set('errors', ['items' => ['Each product needs a name and a quantity of at least 1.']]);
                $this->redirect('/quote-request');
            }
        }

        // Validate uploaded artwork files
        if (!empty($_FILES['files'])) {
            $files = $_FILES['files'];
            if (!is_array($files['name']) || count($files['name']) > self::MAX_UPLOADS) {
                Session::set('errors', ['files' => ['Too many files. Maximum ' . self::MAX_UPLOADS . ' files allowed.']]);
                $this->redirect('/quote-request');
            }

            foreach ($files['name'] as $i => $name) {
                if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                    Session::set('errors', ['files' => ['One of the uploaded files failed to upload.']]);
                    $this->redirect('/quote-request');
                }
                if ((int) $files['size'][$i] > self::MAX_UPLOAD_SIZE) {
                    Session::set('errors', ['files' => ['Each file must be 20MB or smaller.']]);
                    $this->redirect('/quote-request');
                }
                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                if (!in_array($ext, self::ALLOWED_UPLOAD_EXTENSIONS, true)) {
                    Session::set('errors', ['files' => ['File type not allowed: ' . htmlspecialchars($files['name'][$i])]]);
                    $this->redirect('/quote-request');
                }
            }
        }

        $email = strtolower(trim((string) $_POST['email']));
        $notes = trim((string) ($_POST['notes'] ?? '')) ?: null;

        // ---- Persist the quote, its items and any artwork files ----
        $db = Model::getDB();
        $db->beginTransaction();
        $savedFiles = [];
        try {
            $quoteNumber = $this->nextQuoteNumber($db);
            $customerId = RowSecurity::customerId();

            $stmt = $db->prepare(
                "INSERT INTO quotes (quote_number, customer_id, status, subtotal, discount, tax, grand_total, notes)
                 VALUES (:n, :c, 'pending', 0.00, 0.00, 0.00, 0.00, :notes)"
            );
            $stmt->execute(['n' => $quoteNumber, 'c' => $customerId, 'notes' => $notes]);
            $quoteId = (int) $db->lastInsertId();

            $itemStmt = $db->prepare(
                "INSERT INTO quote_items (quote_id, product_id, name, quantity, price, notes)
                 VALUES (:q, :p, :n, :qt, 0.00, :notes)"
            );
            foreach ($items as $item) {
                $productId = isset($item['product_id']) ? (int) $item['product_id'] : 0;
                if ($productId > 0 && !$this->productExists($db, $productId)) {
                    $productId = 0;
                }
                $itemStmt->execute([
                    'q' => $quoteId,
                    'p' => $productId > 0 ? $productId : null,
                    'n' => mb_substr(trim((string) $item['name']), 0, 255),
                    'qt' => (int) $item['quantity'],
                    'notes' => trim((string) ($item['notes'] ?? '')) ?: null,
                ]);
            }

            // Store artwork outside the webroot so it can never be executed.
            if (!empty($_FILES['files']) && is_array($_FILES['files']['name'])) {
                $dir = BASE_PATH . '/storage/uploads/quote_files/' . $quoteId;
                if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
                    throw new \RuntimeException('Could not create upload directory.');
                }
                $fileStmt = $db->prepare(
                    "INSERT INTO quote_files (quote_id, file_path, file_type) VALUES (:q, :f, :t)"
                );
                foreach ($_FILES['files']['name'] as $i => $name) {
                    if ($_FILES['files']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    $safeName = self::safeFileName((string) $name);
                    $dest = $dir . '/' . $safeName;
                    if (!@move_uploaded_file($_FILES['files']['tmp_name'][$i], $dest)) {
                        throw new \RuntimeException('Failed to store uploaded file.');
                    }
                    $savedFiles[] = $dest;
                    $fileStmt->execute([
                        'q' => $quoteId,
                        'f' => 'quote_files/' . $quoteId . '/' . $safeName,
                        't' => strtolower(pathinfo((string) $name, PATHINFO_EXTENSION)),
                    ]);
                }
            }

            $db->commit();
        } catch (\Throwable $t) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            foreach ($savedFiles as $f) {
                @unlink($f);
            }
            Logger::error('Quote persistence failed: ' . $t->getMessage(), 'quote');
            Session::set('error', 'We could not save your request. Please try again.');
            $this->redirect('/quote-request');
        }

        // Notifications — best-effort, never block the response on SMTP.
        $customerName = trim((string) $_POST['first_name']) . ' ' . trim((string) $_POST['last_name']);
        $appUrl = rtrim((string) ($_ENV['APP_URL'] ?? ''), '/');
        Mailer::sendTemplate($email, 'Quote request received', 'quote_request', [
            'customer_name' => $customerName,
            'quote_id' => $quoteNumber,
            'date' => date('F j, Y'),
            'action_link' => $appUrl . '/quote-request/success',
        ]);
        Mailer::sendTemplate($_ENV['ADMIN_EMAIL'] ?? 'hello@marigoldsignatureng.com', 'New quote request: ' . $quoteNumber, 'admin_new_quote', [
            'customer_name' => $customerName,
            'quote_id' => $quoteNumber,
            'date' => date('F j, Y'),
        ]);

        Logger::info("Quote {$quoteNumber} persisted. Email: {$email} IP: " . ($_SERVER['REMOTE_ADDR'] ?? ''), 'http');
        RateLimiter::clear($rateLimitKey);
        Session::set('success', 'Your quote request has been received. Our sales team will respond within 24-48 hours.');
        $this->redirect('/quote-request/success');
    }

    private function nextQuoteNumber(\PDO $db): string
    {
        for ($i = 0; $i < 5; $i++) {
            $num = 'MS-Q-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
            $stmt = $db->prepare("SELECT COUNT(*) FROM quotes WHERE quote_number = :n");
            $stmt->execute(['n' => $num]);
            if ((int) $stmt->fetchColumn() === 0) {
                return $num;
            }
        }
        throw new \RuntimeException('Could not allocate a unique quote number.');
    }

    private function productExists(\PDO $db, int $id): bool
    {
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE id = :id AND status = 'published'");
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private static function safeFileName(string $name): string
    {
        $base = pathinfo($name, PATHINFO_FILENAME);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $base = preg_replace('/[^A-Za-z0-9._-]+/', '-', $base) ?? 'file';
        $base = trim($base, '-.') ?: 'file';
        $base = mb_substr($base, 0, 80);
        $ext = preg_replace('/[^a-z0-9]+/', '', $ext) ?? '';
        return $base . ($ext !== '' ? '.' . $ext : '');
    }
}
