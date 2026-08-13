<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Logger;
use App\Core\Model;
use App\Service\RateLimiter;

/**
 * Newsletter signups from the storefront (footer / home / popup).
 * Accepts both JSON (fetch) and classic form POSTs, protects against
 * CSRF, honeypot and per-IP flooding, then upserts into `newsletters`.
 */
class NewsletterController extends Controller
{
    private const ALLOWED_SOURCES = ['Footer', 'Home', 'Popup', 'Checkout', 'Quote Request'];

    public function subscribe()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed.'], 405);
        }

        // Honeypot: bots fill hidden fields; humans never do.
        if (!empty(trim((string) ($_POST['website'] ?? '')))) {
            $this->json(['ok' => true, 'message' => "You're subscribed! Welcome to the Marigold circle."]);
        }

        // Per-IP rate limit to prevent scraping / spam.
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $limitKey = 'newsletter_' . hash('sha256', $ip);
        if (RateLimiter::tooManyAttempts($limitKey, 5)) {
            Logger::warning("Newsletter rate-limit hit. IP: {$ip}", 'auth');
            $this->json(['error' => 'Too many requests. Please try again later.'], 429);
        }
        RateLimiter::hit($limitKey, 3600);

        // Accept both JSON (fetch) and form-encoded payloads.
        $data = $this->payload();

        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($data['csrf_token'] ?? '');
        if (!CSRF::verify((string) $token)) {
            $this->json(['error' => 'Invalid CSRF token.'], 401);
        }

        $email = filter_var(trim((string) ($data['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null || mb_strlen($email) > 254) {
            $this->json(['error' => 'Please enter a valid email address.'], 422);
        }

        $source = (string) ($data['source'] ?? 'Footer');
        if (!in_array($source, self::ALLOWED_SOURCES, true)) {
            $source = 'Footer';
        }
        $consent = array_key_exists('consent', $data) ? (bool) $data['consent'] : true;

        $db = Model::getDB();
        $stmt = $db->prepare(
            "INSERT INTO newsletters (email, source, consent, status)
             VALUES (:email, :source, :consent, 'subscribed')
             ON DUPLICATE KEY UPDATE source = :source2, consent = :consent2, status = 'subscribed'"
        );
        $stmt->execute([
            'email'    => strtolower($email),
            'source'   => $source,
            'consent'  => $consent ? 1 : 0,
            'source2'  => $source,
            'consent2' => $consent ? 1 : 0,
        ]);

        Logger::info("Newsletter subscription via {$source}: {$email} IP: {$ip}", 'http');
        $this->json(['ok' => true, 'message' => "You're subscribed! Welcome to the Marigold circle."]);
    }

    /**
     * Read the request body as JSON when a JSON content type is used,
     * otherwise fall back to the posted form fields.
     */
    private function payload(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            $decoded = json_decode((string) $raw, true);
            return is_array($decoded) ? $decoded : [];
        }
        return $_POST;
    }
}
