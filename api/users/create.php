<?php
// /Cognate3/api/users/create.php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['ok'=>false,'message'=>'Method not allowed'], 405);
}

try {
  $in = body_json();

  $full_name = trim((string)($in['full_name'] ?? ''));
  $username  = trim((string)($in['username'] ?? ''));
  $email     = trim((string)($in['email'] ?? ''));
  $role      = trim((string)($in['role'] ?? ''));
  $status    = trim((string)($in['status'] ?? ''));
  $password  = (string)($in['password'] ?? '');

  if ($full_name === '' || $username === '' || $email === '' || $role === '' || $status === '' || $password === '') {
    respond(['ok'=>false,'message'=>'Missing required fields'], 400);
  }

  // validate
  $allowedRoles = ['admin','editor','user'];
  if (!in_array($role, $allowedRoles, true)) {
    respond(['ok'=>false,'message'=>'Invalid role'], 400);
  }
  $allowedStatus = ['active','disabled'];
  if (!in_array($status, $allowedStatus, true)) {
    respond(['ok'=>false,'message'=>'Invalid status'], 400);
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(['ok'=>false,'message'=>'Invalid email'], 400);
  }

  // unique checks (simple)
  $pdo = pdo();
  $q = $pdo->prepare('SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND archived = 0');
  $q->execute([$username, $email]);
  if ((int)$q->fetchColumn() > 0) {
    respond(['ok'=>false,'message'=>'Username or email already exists'], 400);
  }

  $hash = password_hash($password, PASSWORD_BCRYPT);

  $stmt = $pdo->prepare(
    'INSERT INTO users (name, username, email, role, status, password, archived, created_at, updated_at)
     VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), NOW())'
  );
  $stmt->execute([$full_name, $username, $email, $role, $status, $hash]);

  respond(['ok'=>true, 'id'=>(int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
  respond(['ok'=>false,'message'=>$e->getMessage()], 500);
}
