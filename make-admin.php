<?php

/**
 * Create a verified super-admin user from the command line.
 *
 * Usage:
 *   php make-admin.php "First Name" "Last Name" "email@example.com"
 *   (password will be prompted for, hidden input)
 *
 * This script is CLI-only and will refuse to run over HTTP.
 * Run it once on a fresh install, then consider deleting it.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only. Access denied.\n");
}

require __DIR__ . '/vendor/autoload.php';

use App\Core\Env;
use App\Core\Model;

Env::load(__DIR__ . '/.env');

$firstName = $argv[1] ?? null;
$lastName  = $argv[2] ?? null;
$email     = $argv[3] ?? null;

while (empty($firstName)) {
    $firstName = trim(readline("First name: "));
}
while (empty($lastName)) {
    $lastName = trim(readline("Last name: "));
}
while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email = trim(readline("Email: "));
}

echo "Password (min 12 chars): ";
system('stty -echo 2>/dev/null');
$password = trim(fgets(STDIN));
system('stty echo 2>/dev/null');
echo "\n";

if (strlen($password) < 12) {
    fwrite(STDERR, "Error: password must be at least 12 characters.\n");
    exit(1);
}

$cost = (int)($_ENV['BCRYPT_COST'] ?? 12);

$db = Model::getDB();
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

$check = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
$check->execute(['email' => strtolower(trim($email))]);
if ($check->fetchColumn()) {
    fwrite(STDERR, "Error: a user with this email already exists.\n");
    exit(1);
}

$data = random_bytes(16);
$data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
$data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

$db->beginTransaction();
try {
    $db->prepare("
        INSERT INTO users
            (uuid, first_name, last_name, email, password, status,
             email_verified_at, created_at)
        VALUES
            (:uuid, :first_name, :last_name, :email, :password, 'active',
             NOW(), NOW())
    ")->execute([
        'uuid' => $uuid,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => strtolower(trim($email)),
        'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]),
    ]);

    $userId = (int)$db->lastInsertId();

    $role = $db->prepare("SELECT id FROM roles WHERE slug = 'super-admin' LIMIT 1");
    $role->execute();
    $roleId = $role->fetchColumn();

    if (!$roleId) {
        throw new \RuntimeException("Role 'super-admin' not found. Run migrate.php first.");
    }

    $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)")
        ->execute(['user_id' => $userId, 'role_id' => $roleId]);

    $db->commit();
} catch (\Throwable $e) {
    $db->rollBack();
    fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
    exit(1);
}

echo "Super-admin created. ID: $userId  Email: $email\n";
echo "Delete this script now if you are done.\n";
