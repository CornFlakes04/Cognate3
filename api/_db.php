<?php
// Cognate3/api/_db.php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// If front-end is a different origin/port, enable CORS (adjust origin):
// header('Access-Control-Allow-Origin: http://localhost');
// header('Access-Control-Allow-Credentials: true);
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Methods: GET,POST,OPTIONS');
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$DB_HOST = '127.0.0.1';
$DB_NAME = 'company_db';
$DB_USER = 'root';
$DB_PASS = '';

try {
  $pdo = new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ]
  );
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['message' => 'DB connection failed']);
  exit;
}

function json_input(): array {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw ?: '[]', true);
  return is_array($data) ? $data : [];
}
function respond(int $code, array $payload): void {
  http_response_code($code);
  echo json_encode($payload);
  exit;
}
