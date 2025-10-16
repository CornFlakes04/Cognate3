<?php
declare(strict_types=1);
require __DIR__ . '/../_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(405, ['message' => 'Method not allowed']);
}

$d = json_input();

$name        = trim($d['name'] ?? '');
$sku         = trim($d['sku'] ?? '');
$price       = (float)($d['price'] ?? 0);
$date_added  = trim($d['date_added'] ?? ''); // 'YYYY-MM-DD' or ''
$categories  = trim($d['categories'] ?? '');
$description = trim($d['description'] ?? '');

if ($name === '' || $sku === '') {
  respond(400, ['message' => 'Name and SKU are required.']);
}

try {
  $stmt = $pdo->prepare("
    INSERT INTO products (name, sku, price, date_added, categories, description)
    VALUES (:name, :sku, :price, NULLIF(:date_added,''), :categories, :description)
  ");
  $stmt->execute([
    ':name' => $name,
    ':sku' => $sku,
    ':price' => $price,
    ':date_added' => $date_added,
    ':categories' => $categories,
    ':description' => $description,
  ]);
  respond(200, ['message' => 'Created', 'id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
  // If you get “Create failed”, temporarily return $e->getMessage() to see the exact SQL error.
  respond(500, ['message' => 'Create failed']);
}
