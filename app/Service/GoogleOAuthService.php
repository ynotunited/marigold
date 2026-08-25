<?php

namespace App\Service;

use App\Core\Model;
use App\Core\Session;
use App\Core\Logger;

class GoogleOAuthService
{
    private const TOKEN_URL  = 'https://oauth2.googleapis.com/token';
    private const PROFILE_URL = 'https://www.googleapis.com/oauth2/v2/userinfo';
    private const AUTH_URL    = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * Build the Google OAuth2 authorization URL with a CSRF state token.
     */
    public static function getAuthorizationUrl(): string
    {
        $clientId    = $_ENV['GOOGLE_CLIENT_ID'] ?? '';
        $redirectUri = $_ENV['GOOGLE_REDIRECT_URI'] ?? '';
        $scope       = 'openid email profile';

        // Generate and store a random state for CSRF protection
        $state = bin2hex(random_bytes(32));
        Session::set('google_oauth_state', $state);

        return self::AUTH_URL . '?' . http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => $scope,
            'state'         => $state,
            'prompt'        => 'select_account',
            'access_type'   => 'online',
        ]);
    }

    /**
     * Validate the OAuth state parameter to prevent CSRF.
     */
    public static function validateState(?string $state): bool
    {
        $stored = Session::get('google_oauth_state');
        Session::remove('google_oauth_state');
        return $state && $stored && hash_equals($stored, $state);
    }

    /**
     * Exchange an authorization code for a user profile.
     *
     * @return array{email: string, first_name: string, last_name: string, avatar: string, google_id: string}|null
     */
    public static function getUserProfile(string $code): ?array
    {
        $tokenData = self::exchangeCode($code);
        if (!$tokenData || empty($tokenData['access_token'])) {
            Logger::warning('Google token exchange failed', 'auth');
            return null;
        }

        return self::fetchProfile($tokenData['access_token']);
    }

    /**
     * Find an existing user by Google ID or email, or create a new one.
     * Returns ['user' => array, 'created' => bool].
     */
    public static function findOrCreateUser(array $profile): array
    {
        $db = Model::getDB();
        $email     = strtolower(trim($profile['email']));
        $googleId  = $profile['google_id'];
        $firstName = trim($profile['first_name'] ?: '');
        $lastName  = trim($profile['last_name'] ?: '');
        $avatar    = $profile['avatar'] ?? null;

        // 1) Match by google_id
        $stmt = $db->prepare("SELECT * FROM users WHERE google_id = :gid AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['gid' => $googleId]);
        $user = $stmt->fetch();
        if ($user) {
            return ['user' => $user, 'created' => false];
        }

        // 2) Match by email (existing password account)
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user) {
            // Link Google ID to existing account
            $db->prepare("UPDATE users SET google_id = :gid, avatar = COALESCE(:avatar2, avatar) WHERE id = :id")
                ->execute(['gid' => $googleId, 'avatar2' => $avatar, 'id' => $user['id']]);
            $user['google_id'] = $googleId;
            if ($avatar && !$user['avatar']) {
                $user['avatar'] = $avatar;
            }
            return ['user' => $user, 'created' => false];
        }

        // 3) Create new user
        $uuid = self::uuid();
        $dummyPassword = password_hash(bin2hex(random_bytes(32)), PASSWORD_BCRYPT, ['cost' => 12]);
        $tz = \App\Core\Timezone::detectFromRequest();

        $db->beginTransaction();
        try {
            $db->prepare("
                INSERT INTO users
                    (uuid, first_name, last_name, email, password, avatar, google_id,
                     status, email_verified_at, timezone, created_at)
                VALUES
                    (:uuid, :first_name, :last_name, :email, :password, :avatar, :google_id,
                     'active', NOW(), :tz, NOW())
            ")->execute([
                'uuid'       => $uuid,
                'first_name' => $firstName ?: 'Google',
                'last_name'  => $lastName ?: 'User',
                'email'      => $email,
                'password'   => $dummyPassword,
                'avatar'     => $avatar,
                'google_id'  => $googleId,
                'tz'         => $tz,
            ]);

            $userId = (int)$db->lastInsertId();

            $role = $db->prepare("SELECT id FROM roles WHERE slug = 'customer' LIMIT 1");
            $role->execute();
            if ($roleId = $role->fetchColumn()) {
                $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:uid, :rid)")
                    ->execute(['uid' => $userId, 'rid' => $roleId]);
            }

            $db->prepare("INSERT INTO customers (user_id, created_at) VALUES (:uid, NOW())")
                ->execute(['uid' => $userId]);

            $db->commit();

            // Re-fetch the full user row
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            Logger::info("New user registered via Google. ID: $userId Email: $email", 'auth');
            return ['user' => $user, 'created' => true];
        } catch (\Throwable $e) {
            $db->rollBack();
            Logger::error('Google registration failed: ' . $e->getMessage(), 'auth');
            throw $e;
        }
    }

    /**
     * Exchange authorization code for tokens via curl.
     */
    private static function exchangeCode(string $code): ?array
    {
        $ch = curl_init(self::TOKEN_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS     => http_build_query([
                'code'          => $code,
                'client_id'     => $_ENV['GOOGLE_CLIENT_ID'] ?? '',
                'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? '',
                'redirect_uri'  => $_ENV['GOOGLE_REDIRECT_URI'] ?? '',
                'grant_type'    => 'authorization_code',
            ]),
        ]);

        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err || !$body) {
            Logger::warning("Google token curl error: $err", 'auth');
            return null;
        }

        return json_decode($body, true);
    }

    /**
     * Fetch the user's Google profile using an access token.
     */
    private static function fetchProfile(string $accessToken): ?array
    {
        $ch = curl_init(self::PROFILE_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => ["Authorization: Bearer $accessToken"],
        ]);

        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err || !$body) {
            Logger::warning("Google profile curl error: $err", 'auth');
            return null;
        }

        $data = json_decode($body, true);
        if (!is_array($data) || empty($data['email'])) {
            Logger::warning('Google profile response missing email', 'auth');
            return null;
        }

        // Split name into first/last
        $fullName = trim($data['name'] ?? '');
        $parts    = preg_split('/\s+/', $fullName, 2);
        $first    = $parts[0] ?? '';
        $last     = $parts[1] ?? '';

        return [
            'email'      => $data['email'],
            'first_name' => $first,
            'last_name'  => $last,
            'avatar'     => $data['picture'] ?? null,
            'google_id'  => (string)($data['id'] ?? ''),
        ];
    }

    private static function uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
