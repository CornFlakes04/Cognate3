<?php
declare(strict_types=1);
require __DIR__ . '/../_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(405, ['message' => 'Method not allowed']);
}

$d = json_input();
$id = (int)($d['id'] ?? 0);
if ($id <= 0) respond(400, ['message' => 'Invalid id']);

try {
  $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
  $stmt->execute([':id' => $id]);
  respond(200, ['message' => 'Deleted']);
} catch (Throwable $e) {
  respond(500, ['message' => 'Delete failed']);
}
