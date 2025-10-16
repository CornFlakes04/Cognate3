<?php
declare(strict_types=1);
require __DIR__ . '/../_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(405, ['message' => 'Method not allowed']);
}

$d = json_input();

$id          = (int)($d['id'] ?? 0);
$name        = trim($d['name'] ?? '');
$sku         = trim($d['sku'] ?? '');
$price       = (float)($d['price'] ?? 0);
$date_added  = trim($d['date_added'] ?? '');
$categories  = trim($d['categories'] ?? '');
$description = trim($d['description'] ?? '');

if ($id <= 0)                    respond(400, ['message' => 'Invalid id']);
if ($name === '' || $sku === '') respond(400, ['message' => 'Name and SKU are required.']);

try {
  $stmt = $pdo->prepare("
    UPDATE products SET
      name=:name, sku=:sku, price=:price, date_added=NULLIF(:date_added,''),
      categories=:categories, description=:description
    WHERE id=:id
  ");
  $stmt->execute([
    ':id' => $id,
    ':name' => $name,
    ':sku' => $sku,
    ':price' => $price,
    ':date_added' => $date_added,
    ':categories' => $categories,
    ':description' => $description,
  ]);
  respond(200, ['message' => 'Updated']);
} catch (Throwable $e) {
  respond(500, ['message' => 'Update failed']);
}
