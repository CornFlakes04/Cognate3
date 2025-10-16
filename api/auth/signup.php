<?php
declare(strict_types=1);
require_once __DIR__ . '/../_db.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
  // allow your JS probe to see the endpoint is up
  respond(200, ['ok' => true, 'endpoint' => 'signup']);
}
if ($method !== 'POST') {
  respond(405, ['message' => 'Method not allowed']);
}

$in = json_input();
$name     = trim($in['name'] ?? '');
$username = trim($in['username'] ?? '');
$email    = strtolower(trim($in['email'] ?? ''));
$pass     = (string)($in['password'] ?? '');

if ($name === '' || $username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
  respond(400, ['message' => 'Invalid input']);
}

// conflict check
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
  respond(409, ['message' => 'Email or username already exists']);
}

// hash + insert (your table column is `password`)
$hash = password_hash($pass, PASSWORD_DEFAULT);

// If your schema requires `theme` NOT NULL without default, include a default:
$stmt = $pdo->prepare('INSERT INTO users (username, name, email, password) VALUES (?,?,?,?)');
$stmt->execute([$username, $name, $email, $hash]);

respond(201, ['message' => 'Account created']);
