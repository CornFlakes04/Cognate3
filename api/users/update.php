<?php
// /Cognate3/api/users/update.php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['ok'=>false,'message'=>'Method not allowed'], 405);
}

try {
  $in = body_json();

  $id = (int)($in['id'] ?? 0);
  if ($id <= 0) respond(['ok'=>false,'message'=>'Invalid id'], 400);

  // Accept only fields that were provided
  $fields = [];
  $params = [];

  if (isset($in['full_name'])) { // map JSON -> DB
    $name = trim((string)$in['full_name']);
    if ($name === '') respond(['ok'=>false,'message'=>'full_name cannot be empty'], 400);
    $fields[] = 'name = ?';
    $params[] = $name;
  }
  if (isset($in['username'])) {
    $username = trim((string)$in['username']);
    if ($username === '') respond(['ok'=>false,'message'=>'username cannot be empty'], 400);
    $fields[] = 'username = ?';
    $params[] = $username;
  }
  if (isset($in['email'])) {
    $email = trim((string)$in['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respond(['ok'=>false,'message'=>'Invalid email'], 400);
    $fields[] = 'email = ?';
    $params[] = $email;
  }
  if (isset($in['role'])) {
    $role = trim((string)$in['role']);
    $allowedRoles = ['admin','editor','user'];
    if (!in_array($role, $allowedRoles, true)) respond(['ok'=>false,'message'=>'Invalid role'], 400);
    $fields[] = 'role = ?';
    $params[] = $role;
  }
  if (isset($in['status'])) {
    $status = trim((string)$in['status']);
    $allowedStatus = ['active','disabled'];
    if (!in_array($status, $allowedStatus, true)) respond(['ok'=>false,'message'=>'Invalid status'], 400);
    $fields[] = 'status = ?';
    $params[] = $status;
  }
  if (isset($in['password'])) {
    $pass = (string)$in['password'];
    if ($pass !== '') {
      $fields[] = 'password = ?';
      $params[] = password_hash($pass, PASSWORD_BCRYPT);
    }
  }

  if (!$fields) {
    respond(['ok'=>false,'message'=>'No fields to update'], 400);
  }

  // Optional uniqueness re-checks when username/email are changing
  $pdo = pdo();
  if (isset($in['username']) || isset($in['email'])) {
    $u = $in['username'] ?? null;
    $e = $in['email'] ?? null;
    if ($u || $e) {
      $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM users WHERE archived = 0 AND id <> ? AND (username = COALESCE(?, username) OR email = COALESCE(?, email))'
      );
      $stmt->execute([$id, $u, $e]);
      if ((int)$stmt->fetchColumn() > 0) {
        respond(['ok'=>false,'message'=>'Username or email already in use'], 400);
      }
    }
  }

  $sql = 'UPDATE users SET '.implode(', ', $fields).', updated_at = NOW() WHERE id = ? AND archived = 0';
  $params[] = $id;
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  respond(['ok'=>true]);
} catch (Throwable $e) {
  respond(['ok'=>false,'message'=>$e->getMessage()], 500);
}
