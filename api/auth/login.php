<?php
declare(strict_types=1);

require __DIR__ . '/../_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(405, ['message' => 'Method not allowed']);
}

$in = json_input();
$username = trim((string)($in['username'] ?? ''));
$password = (string)($in['password'] ?? '');

if ($username === '' || $password === '') {
  respond(400, ['message' => 'Missing username/password']);
}

$stmt = $pdo->prepare(
  'SELECT id, username, name, email, password, last_login
   FROM users WHERE username = ? LIMIT 1'
);
$stmt->execute([$username]);
$user = $stmt->fetch();
if (!$user) respond(401, ['message' => 'Invalid credentials']);

// Your DB stores bcrypt hashes -> verify properly
$stored = (string)$user['password'];
$ok = password_verify($password, $stored);

if (!$ok) respond(401, ['message' => 'Invalid credentials']);

$pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([(int)$user['id']]);

$_SESSION['user'] = $user['username']; // used for admin gating

respond(200, [
  'ok' => true,
  'user' => [
    'username' => $user['username'],
    'name'     => $user['name'],
    'email'    => $user['email'],
    'is_admin' => $user['username'] === 'admin'
  ]
]);
