<?php
// /Cognate3/api/auth/login.php
declare(strict_types=1);

require_once __DIR__ . '/../_bootstrap.php';

// Never echo PHP warnings to the client (they break JSON)
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['ok' => false, 'message' => 'Method not allowed'], 405);
}

// Accept JSON or form-POST
$in = body_json();
if (!$in && !empty($_POST)) $in = $_POST;

$username = trim((string)($in['username'] ?? ''));
$password = (string)($in['password'] ?? '');

if ($username === '' || $password === '') {
  respond(['ok' => false, 'message' => 'Missing username/password'], 400);
}

try {
  $db = pdo();

  // detect column names to match your schema
  $hasFullName = has_col('users', 'full_name');
  $hasName     = has_col('users', 'name');
  $nameCol     = $hasFullName ? 'full_name' : ($hasName ? 'name' : null);

  $hasPWHash   = has_col('users', 'password_hash');
  $hasPWPlain  = has_col('users', 'password');
  $passCol     = $hasPWHash ? 'password_hash' : ($hasPWPlain ? 'password' : null);

  $hasRole     = has_col('users', 'role');
  $hasStatus   = has_col('users', 'status');
  $hasEmail    = has_col('users', 'email');
  $hasLast     = has_col('users', 'last_login');

  if ($passCol === null) {
    // We cannot authenticate without a password column
    respond(['ok' => false, 'message' => 'Server not configured for auth (no password column)'], 500);
  }

  // Build SELECT list safely
  $select = ['id', 'username', $passCol . ' AS _pw'];
  if ($nameCol) $select[] = "$nameCol AS _name";
  if ($hasEmail) $select[] = 'email';
  if ($hasRole)  $select[] = 'role';
  if ($hasStatus)$select[] = 'status';

  $sql = 'SELECT ' . implode(', ', $select) . ' FROM users WHERE username = :u LIMIT 1';
  $stmt = $db->prepare($sql);
  $stmt->execute([':u' => $username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // No such user
  if (!$user) {
    respond(['ok' => false, 'message' => 'Invalid credentials'], 401);
  }

  // Verify password (supports bcrypt in password_hash OR legacy password column with bcrypt)
  $stored = (string)($user['_pw'] ?? '');
  $ok = false;

  if ($hasPWHash) {
    $ok = password_verify($password, $stored);
  } else {
    // If your legacy `password` column already contains bcrypt hashes, this still works.
    // If it's plaintext (not recommended), allow a transitional check (remove once migrated).
    $ok = password_verify($password, $stored) || hash_equals($stored, $password);
  }

  if (!$ok) {
    respond(['ok' => false, 'message' => 'Invalid credentials'], 401);
  }

  // Optional: block disabled accounts (if status column exists)
  if ($hasStatus && isset($user['status']) && $user['status'] !== 'active') {
    respond(['ok' => false, 'message' => 'Account disabled'], 403);
  }

  // Session
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  $_SESSION['user_id'] = (int)$user['id'];
  $_SESSION['role']    = (string)($user['role'] ?? 'user');

  // Update last_login if column exists
  if ($hasLast) {
    $db->prepare('UPDATE users SET last_login = NOW() WHERE id = :id')
       ->execute([':id' => (int)$user['id']]);
  }

  respond([
    'ok' => true,
    'user' => [
      'id'        => (int)$user['id'],
      'username'  => (string)$user['username'],
      'full_name' => (string)($user['_name'] ?? ''),
      'email'     => (string)($user['email'] ?? ''),
      'role'      => (string)($user['role'] ?? ''),
      'status'    => (string)($user['status'] ?? '')
    ]
  ]);
} catch (Throwable $e) {
  // Log internally so you can see the real error in your PHP error log
  error_log('[auth/login] ' . $e->getMessage());
  respond(['ok' => false, 'message' => 'Server error'], 500);
}
