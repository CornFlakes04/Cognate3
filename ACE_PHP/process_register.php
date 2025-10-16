<?php
// process_register.php
require __DIR__ . '/db.php';

function go_back($msg){
  header('Location: register.php?err=' . urlencode($msg));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  go_back('Invalid request.');
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$pass     = $_POST['password'] ?? '';
$confirm  = $_POST['confirm'] ?? '';

// Basic validation
if ($username === '' || $email === '' || $pass === '' || $confirm === '') {
  go_back('All fields are required.');
}
if (!preg_match('/^[A-Za-z0-9_\.]{3,50}$/', $username)) {
  go_back('Username must be 3–50 chars (letters, numbers, underscore, dot).');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
  go_back('Enter a valid email (max 100 chars).');
}
if (strlen($pass) < 8) {
  go_back('Password must be at least 8 characters.');
}
if ($pass !== $confirm) {
  go_back('Passwords do not match.');
}

// Check duplicates
$stmt = $pdo->prepare('SELECT 1 FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
  go_back('Username or email already in use.');
}

// Hash and insert
$hash = password_hash($pass, PASSWORD_DEFAULT);

$ins = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:u, :e, :p)');
$ins->execute([':u'=>$username, ':e'=>$email, ':p'=>$hash]);

header('Location: success.php?u=' . urlencode($username));
