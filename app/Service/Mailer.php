<?php

namespace App\Service;

use App\Core\Logger;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Shared mailer. All transactional email flows (auth, quotes, orders) use this
 * so SMTP config lives in exactly one place. Sending failures are logged, never
 * thrown — an email must never take down a request (e.g. a webhook).
 */
class Mailer
{
    public static function send(string $to, string $subject, string $bodyHtml, ?string $altText = null): void
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'] ?? 'localhost';
            $mail->SMTPAuth = !empty($_ENV['SMTP_USER']);
            $mail->Username = $_ENV['SMTP_USER'] ?? '';
            $mail->Password = $_ENV['SMTP_PASS'] ?? $_ENV['SMTP_PASSWORD'] ?? '';
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'] ?? PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int) ($_ENV['SMTP_PORT'] ?? 587);
            $mail->setFrom(
                $_ENV['SMTP_FROM_EMAIL'] ?? 'no-reply@marigoldsignature.com',
                $_ENV['SMTP_FROM_NAME'] ?? 'Marigold Signature'
            );
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body = $bodyHtml;
            $mail->AltBody = $altText ?? htmlspecialchars(strip_tags(str_replace(['<br>', '<br/>', '</p>', '</div>'], "\n", $bodyHtml)), ENT_QUOTES, 'UTF-8');
            $mail->isHTML(true);
            $mail->send();
        } catch (\Throwable $e) {
            Logger::error('Email to ' . $to . ' failed: ' . $e->getMessage(), 'email');
        }
    }

    /**
     * Render an email template from app/View/emails/templates and send it.
     *
     * @param string $template template file name WITHOUT the .php suffix
     */
    public static function sendTemplate(string $to, string $subject, string $template, array $data = []): void
    {
        $base = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
        $file = $base . '/app/View/emails/templates/' . $template . '.php';

        if (!is_readable($file)) {
            Logger::error('Email template not found: ' . $file, 'email');
            return;
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        $html = (string) ob_get_clean();

        self::send($to, $subject, $html);
    }
}
