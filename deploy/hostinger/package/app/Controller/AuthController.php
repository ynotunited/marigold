<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Logger;
use App\Service\AuthService;
use App\Service\RateLimiter;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::get('user_id') || AuthService::loginWithCookie()) {
            $this->redirect('/account/dashboard');
        }

        $this->view('auth/login', [
            'csrf_token' => CSRF::field()
        ]);
    }

    public function showRegister()
    {
        $this->view('auth/register', [
            'csrf_token' => CSRF::field()
        ]);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $this->verifyCsrf();

        // Honeypot: bots fill hidden fields; humans never do
        if (!empty(trim((string)($_POST['website'] ?? '')))) {
            Logger::warning('Registration honeypot triggered. IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''), 'http');
            $this->redirect('/register');
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $rateLimitKey = 'register_' . hash('sha256', $ip);
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            Logger::warning("Registration rate-limit hit. IP: $ip", 'auth');
            Session::set('error', 'Too many registration attempts. Please try again later.');
            $this->redirect('/register');
        }

        $validator = new Validator();
        if (!$validator->validate($_POST, [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email|max:254',
            'password' => 'required|min:12|max:1024'
        ])) {
            Session::set('errors', $validator->getErrors());
            $this->redirect('/register');
        }

        $result = AuthService::register([
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'password' => $_POST['password'] ?? '',
        ]);

        if (isset($result['error'])) {
            Session::set('errors', ['email' => [$result['error']]]);
            RateLimiter::hit($rateLimitKey, 3600);
            $this->redirect('/register');
        }

        RateLimiter::clear($rateLimitKey);
        $this->sendVerificationEmail($_POST['email'], $result['verify_token']);

        Session::set('success', 'Account created. Please check your email to verify your address before signing in.');
        $this->redirect('/login');
    }

    public function verifyEmail()
    {
        $token = trim($_GET['token'] ?? '');
        if (!$token || !AuthService::verifyEmail($token)) {
            Session::set('error', 'This verification link is invalid or has expired.');
            $this->redirect('/login');
        }

        Session::set('success', 'Email verified. You can now sign in.');
        $this->redirect('/login');
    }

    public function showForgotPassword()
    {
        $this->view('auth/forgot-password', [
            'csrf_token' => CSRF::field()
        ]);
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }

        $this->verifyCsrf();

        // Honeypot: bots fill hidden fields; humans never do
        if (!empty(trim((string)($_POST['website'] ?? '')))) {
            Logger::warning('Password-reset honeypot triggered. IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''), 'http');
            $this->redirect('/forgot-password');
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $rateLimitKey = 'password_reset_' . hash('sha256', $email . '|' . $ip);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            Logger::warning("Password-reset rate-limit hit. Email: $email IP: $ip", 'auth');
        }

        if (!RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            RateLimiter::hit($rateLimitKey, 3600);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $token = AuthService::createPasswordResetToken($email);
                if ($token) {
                    Logger::info("Password reset link issued. Email: $email IP: $ip", 'auth');
                    $this->sendPasswordResetEmail($email, $token);
                }
            }
        }

        Session::set('success', 'If that email exists, a reset link will be sent.');
        $this->redirect('/login');
    }

    public function showResetPassword()
    {
        $token = trim($_GET['token'] ?? '');

        $this->view('auth/reset-password', [
            'csrf_token' => CSRF::field(),
            'token' => preg_match('/^[a-f0-9]{64}$/i', $token) ? $token : ''
        ]);
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reset-password');
        }

        $this->verifyCsrf();

        $token = trim($_POST['token'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $confirm = (string)($_POST['password_confirmation'] ?? '');

        if (!preg_match('/^[a-f0-9]{64}$/i', $token)) {
            Session::set('error', 'This reset link is invalid or has expired.');
            $this->redirect('/forgot-password');
        }

        if ($password !== $confirm) {
            Session::set('errors', ['password' => ['Passwords do not match.']]);
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        if (strlen($password) < 12 || strlen($password) > 1024) {
            Session::set('errors', ['password' => ['Password must be between 12 and 1024 characters.']]);
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        if (!AuthService::resetPassword($token, $password)) {
            Session::set('error', 'This reset link is invalid or has expired, or the password is too short.');
            $this->redirect('/forgot-password');
        }

        Session::set('success', 'Password reset successfully. Please sign in again.');
        $this->redirect('/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $this->verifyCsrf();

        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $rateLimitKey = 'login_' . hash('sha256', $email . '|' . $ip);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            Logger::warning("Login rate-limit hit. Email: $email IP: $ip", 'auth');
            Session::set('error', 'Too many failed login attempts. Please try again later.');
            $this->redirect('/login');
        }

        $validator = new Validator();
        if (!$validator->validate($_POST, [
            'email' => 'required|email|max:254',
            'password' => 'required|max:1024'
        ])) {
            Session::set('errors', $validator->getErrors());
            $this->redirect('/login');
        }

        if (AuthService::login($email, $password, $remember)) {
            RateLimiter::clear($rateLimitKey);
            $this->redirect('/account/dashboard');
        }

        RateLimiter::hit($rateLimitKey, 300);
        Session::set('error', 'Invalid email or password.');
        $this->redirect('/login');
    }

    public function logout()
    {
        $this->verifyCsrf();
        AuthService::logout();
        $this->redirect('/login');
    }

    private function verifyCsrf(): void
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }
    }

    private function sendVerificationEmail(string $email, string $token): void
    {
        $link = rtrim($_ENV['APP_URL'] ?? $this->requestBaseUrl(), '/') . '/verify-email?token=' . urlencode($token);
        $this->sendAuthEmail($email, 'Verify your Marigold Signature account', "Verify your email by opening this link: $link");
    }

    private function sendPasswordResetEmail(string $email, string $token): void
    {
        $link = rtrim($_ENV['APP_URL'] ?? $this->requestBaseUrl(), '/') . '/reset-password?token=' . urlencode($token);
        $this->sendAuthEmail($email, 'Reset your Marigold Signature password', "Reset your password by opening this link within 1 hour: $link");
    }

    private function sendAuthEmail(string $email, string $subject, string $body): void
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'] ?? 'localhost';
            $mail->SMTPAuth = !empty($_ENV['SMTP_USER']);
            $mail->Username = $_ENV['SMTP_USER'] ?? '';
            $mail->Password = $_ENV['SMTP_PASS'] ?? $_ENV['SMTP_PASSWORD'] ?? '';
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'] ?? PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int)($_ENV['SMTP_PORT'] ?? 587);
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'] ?? 'no-reply@marigoldsignatureng.com', $_ENV['SMTP_FROM_NAME'] ?? 'Marigold Signature');
            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->Body = nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));
            $mail->AltBody = $body;
            $mail->isHTML(true);
            $mail->send();
        } catch (\Throwable $e) {
            Logger::error('Auth email failed: ' . $e->getMessage(), 'email');
        }
    }

    private function requestBaseUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
}
