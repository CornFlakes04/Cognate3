<?php
session_start();
if (empty($_SESSION['loggedin']) || empty($_SESSION['id'])) {
  http_response_code(401);
  exit('Not logged in');
}

$payload = json_decode(file_get_contents('php://input'), true);
$theme = $payload['theme'] ?? 'system';
if (!in_array($theme, ['light','dark','system'], true)) {
  http_response_code(400);
  exit('Bad theme');
}

require __DIR__ . '/db.php';
$stmt = $pdo->prepare('UPDATE users SET theme = ? WHERE id = ?');
$stmt->execute([$theme, (int)$_SESSION['id']]);

header('Content-Type: application/json');
echo json_encode(['ok' => true]);
