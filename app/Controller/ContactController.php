<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Logger;
use App\Core\Model;
use App\Core\Validator;
use App\Service\RateLimiter;

/**
 * Contact form submissions from the /contact page. Validates, rate-limits
 * and persists into `contact_messages` for review in the admin backend.
 */
class ContactController extends Controller
{
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed.'], 405);
        }

        // Honeypot: bots fill hidden fields; humans never do.
        if (!empty(trim((string) ($_POST['website'] ?? '')))) {
            $this->json(['ok' => true, 'message' => 'Message sent — our team will respond within 1 business day.']);
        }

        // Per-IP rate limit to prevent scraping / spam.
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $limitKey = 'contact_' . hash('sha256', $ip);
        if (RateLimiter::tooManyAttempts($limitKey, 5)) {
            Logger::warning("Contact form rate-limit hit. IP: {$ip}", 'auth');
            $this->json(['error' => 'Too many requests. Please try again later.'], 429);
        }
        RateLimiter::hit($limitKey, 3600);

        // Accept both JSON (fetch) and form-encoded payloads.
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $data = [];
        if (stripos($contentType, 'application/json') !== false) {
            $decoded = json_decode((string) file_get_contents('php://input'), true);
            $data = is_array($decoded) ? $decoded : [];
        } else {
            $data = $_POST;
        }

        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($data['csrf_token'] ?? '');
        if (!CSRF::verify((string) $token)) {
            $this->json(['error' => 'Invalid CSRF token.'], 401);
        }

        $validator = new Validator();
        if (!$validator->validate($data, [
            'name'    => 'required|max:150',
            'company' => 'max:150',
            'email'   => 'required|email',
            'phone'   => 'max:30',
            'subject' => 'max:150',
            'message' => 'required|min:10|max:5000',
        ])) {
            $this->json(['error' => $validator->getErrors()], 422);
        }

        $db = Model::getDB();
        $stmt = $db->prepare(
            "INSERT INTO contact_messages (name, company, email, phone, subject, message, ip_address)
             VALUES (:name, :company, :email, :phone, :subject, :message, :ip)"
        );
        $stmt->execute([
            'name'    => trim($data['name']),
            'company' => trim((string) ($data['company'] ?? '')) ?: null,
            'email'   => strtolower(trim($data['email'])),
            'phone'   => trim((string) ($data['phone'] ?? '')) ?: null,
            'subject' => trim((string) ($data['subject'] ?? '')) ?: null,
            'message' => trim($data['message']),
            'ip'      => $ip,
        ]);

        Logger::info("Contact message from {$data['email']} IP: {$ip}", 'http');
        $this->json(['ok' => true, 'message' => 'Message sent — our team will respond within 1 business day.']);
    }
}
