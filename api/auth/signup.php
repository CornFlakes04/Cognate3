<?php
// /Cognate3/api/auth/signup.php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php'; // pdo(), respond(), body_json(), has_col()

$table = 'users';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['ok' => false, 'message' => 'Method not allowed'], 405);
}

$in = body_json();

/* ---------- Availability check ---------- */
if (!empty($in['check_only']) && isset($in['username'])) {
  $username = trim((string)$in['username']);
  if ($username === '') respond(['ok' => true, 'exists' => false]);

  $stmt = pdo()->prepare("SELECT 1 FROM `$table` WHERE `username`=? LIMIT 1");
  $stmt->execute([$username]);
  respond(['ok' => true, 'exists' => (bool)$stmt->fetchColumn()]);
}

/* ---------- Inputs ---------- */
$name     = trim((string)($in['name']     ?? ''));
$username = trim((string)($in['username'] ?? ''));
$email    = trim((string)($in['email']    ?? ''));
$password =        (string)($in['password'] ?? '');

if ($name === '' || $username === '' || $email === '' || $password === '') {
  respond(['ok' => false, 'message' => 'Missing required fields'], 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  respond(['ok' => false, 'message' => 'Invalid email'], 400);
}

/* ---------- Column mapping (auto-detect) ---------- */
$colName = has_col($table, 'full_name') ? 'full_name'
        : (has_col($table, 'name') ? 'name' : null);

if ($colName === null) {
  respond(['ok' => false, 'message' => "Your `$table` table needs a `full_name` or `name` column."], 500);
}

$hasRole      = has_col($table, 'role');
$hasStatus    = has_col($table, 'status');
$hasCreatedAt = has_col($table, 'created_at');
$hasUpdatedAt = has_col($table, 'updated_at');

/* ---------- Uniqueness checks ---------- */
$pdo = pdo();

// username unique
$stmt = $pdo->prepare("SELECT 1 FROM `$table` WHERE `username`=? LIMIT 1");
$stmt->execute([$username]);
if ($stmt->fetchColumn()) {
  respond(['ok' => false, 'message' => 'Username already exists'], 409);
}

// email unique (if column exists)
if (has_col($table, 'email')) {
  $stmt = $pdo->prepare("SELECT 1 FROM `$table` WHERE `email`=? LIMIT 1");
  $stmt->execute([$email]);
  if ($stmt->fetchColumn()) {
    respond(['ok' => false, 'message' => 'Email already exists'], 409);
  }
}

/* ---------- Defaults ---------- */
$hash   = password_hash($password, PASSWORD_BCRYPT);
$role   = 'user';      // default role for new signups
$status = 'active';    // default status

/* ---------- Build dynamic INSERT that matches your columns ---------- */
$cols = [$colName, 'username', 'email', 'password'];
$vals = [$name,    $username,  $email,  $hash   ];
$ph   = ['?',      '?',        '?',     '?'     ];

if ($hasRole)      { $cols[] = 'role';       $vals[] = $role;   $ph[] = '?'; }
if ($hasStatus)    { $cols[] = 'status';     $vals[] = $status; $ph[] = '?'; }
if ($hasCreatedAt) { $cols[] = 'created_at'; $ph[]   = 'NOW()'; }
if ($hasUpdatedAt) { $cols[] = 'updated_at'; $ph[]   = 'NOW()'; }

$sql = 'INSERT INTO `'.$table.'` (`'.implode('`,`', $cols).'`) VALUES ('.implode(',', $ph).')';

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($vals);
  $id = (int)$pdo->lastInsertId();
  respond(['ok' => true, 'id' => $id], 201);
} catch (Throwable $e) {
  // Return the actual DB error to help you fix schema mismatches during setup.
  respond([
    'ok'      => false,
    'message' => 'Insert failed',
    'error'   => $e->getMessage(),
    'sql'     => $sql
  ], 500);
}
