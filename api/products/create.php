<?php
require __DIR__ . '/../db.php';

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$name = trim($input['name'] ?? '');
$sku  = trim($input['sku'] ?? '');
$price = $input['price'] ?? null;
$date_added = trim($input['date_added'] ?? '');
$categories = trim($input['categories'] ?? '');
$desc = trim($input['description'] ?? '');
$specs = trim($input['specs'] ?? '');
$image = trim($input['image'] ?? ''); // this is the URL path returned by upload.php

if ($name === '' || $sku === '' || $categories === '' || !is_numeric($price)) {
  json_out(400, ['ok'=>false, 'message'=>'Missing required fields']);
}

try {
  $sql = "INSERT INTO products
            (name, sku, price, date_added, categories, short_description, specs, image_path)
          VALUES
            (:name, :sku, :price, NULLIF(:date_added,''), :categories, :desc, :specs, NULLIF(:image,''))";
  $st = db()->prepare($sql);
  $st->execute([
    ':name'=>$name, ':sku'=>$sku, ':price'=>$price,
    ':date_added'=>$date_added, ':categories'=>$categories,
    ':desc'=>$desc, ':specs'=>$specs, ':image'=>$image
  ]);

  json_out(200, ['ok'=>true, 'id'=> (int)db()->lastInsertId()]);
} catch (Throwable $e) {
  // Duplicate SKU etc.
  json_out(400, ['ok'=>false, 'message'=>$e->getMessage()]);
}
