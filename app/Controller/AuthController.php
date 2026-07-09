<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\CSRF;
use App\Core\Session;
use App\Service\AuthService;
use App\Service\RateLimiter;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // If already logged in, redirect
        if (Session::get('user_id') || AuthService::loginWithCookie()) {
            $this->redirect('/account/dashboard');
        }

        $this->view('auth/login', [
            'csrf_token' => CSRF::field()
        ]);
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        $this->view('auth/register', [
            'csrf_token' => CSRF::field()
        ]);
    }

    /**
     * Handle registration submission
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }

        Session::set('success', 'Registration is not yet connected to persistence.');
        $this->redirect('/login');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        $this->view('auth/forgot-password', [
            'csrf_token' => CSRF::field()
        ]);
    }

    /**
     * Handle forgot password submission
     */
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }

        Session::set('success', 'If that email exists, a reset link will be sent.');
        $this->redirect('/login');
    }

    /**
     * Show reset password form
     */
    public function showResetPassword()
    {
        $token = $_GET['token'] ?? '';

        $this->view('auth/reset-password', [
            'csrf_token' => CSRF::field(),
            'token' => $token
        ]);
    }

    /**
     * Handle reset password submission
     */
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reset-password');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }

        Session::set('success', 'Password reset is not yet connected to persistence.');
        $this->redirect('/login');
    }

    /**
     * Handle login submission
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $rateLimitKey = 'login_' . md5($ip . $email);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            Session::set('error', 'Too many failed login attempts. Please try again later.');
            $this->redirect('/login');
        }

        $validator = new Validator();
        if (!$validator->validate($_POST, [
            'email' => 'required|email',
            'password' => 'required'
        ])) {
            Session::set('errors', $validator->getErrors());
            $this->redirect('/login');
        }

        if (AuthService::login($email, $password, $remember)) {
            RateLimiter::clear($rateLimitKey);
            $this->redirect('/account/dashboard');
        } else {
            RateLimiter::hit($rateLimitKey, 300); // 5 mins lockout after 5 attempts
            Session::set('error', 'Invalid email or password.');
            $this->redirect('/login');
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }
        
        AuthService::logout();
        $this->redirect('/login');
    }
}
