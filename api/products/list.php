<?php
// /Cognate3/api/products/list.php
declare(strict_types=1);

require __DIR__ . '/../_db.php';
session_start();

// Gate to admin only (uncomment to require admin session)
/*
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
  http_response_code(403);
  echo json_encode(['message' => 'Forbidden']);
  exit;
}
*/

// Fetch products. Matches your company_db.sql columns:
// id, name, sku, price, date_added, categories (SET),
// short_description/short_desc, specs, image_url/image_path, created_at, updated_at
$sql = "
  SELECT
    id,
    name,
    sku,
    price,
    date_added,
    categories,
    COALESCE(short_description, short_desc) AS description,
    specs,
    COALESCE(image_url, image_path) AS image,
    created_at,
    updated_at
  FROM products
  ORDER BY id DESC
";

$items = $pdo->query($sql)->fetchAll();

http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['ok' => true, 'items' => $items]);
