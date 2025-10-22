<?php
// /Cognate3/api/users/delete.php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['ok'=>false,'message'=>'Method not allowed'], 405);
}

try {
  $in = body_json();
  $id = (int)($in['id'] ?? 0);
  if ($id <= 0) respond(['ok'=>false,'message'=>'Invalid id'], 400);

  $stmt = pdo()->prepare('UPDATE users SET archived = 1, updated_at = NOW() WHERE id = ?');
  $stmt->execute([$id]);

  respond(['ok'=>true]);
} catch (Throwable $e) {
  respond(['ok'=>false,'message'=>$e->getMessage()], 500);
}
