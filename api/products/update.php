<?php
require __DIR__ . '/../db.php';

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$id = (int)($input['id'] ?? 0);
if ($id <= 0) json_out(400, ['ok'=>false, 'message'=>'Invalid id']);

$name = trim($input['name'] ?? '');
$sku  = trim($input['sku'] ?? '');
$price = $input['price'] ?? null;
$date_added = trim($input['date_added'] ?? '');
$categories = trim($input['categories'] ?? '');
$desc = trim($input['description'] ?? '');
$specs = trim($input['specs'] ?? '');
$image = trim($input['image'] ?? ''); // optional; keep old if empty

if ($name === '' || $sku === '' || $categories === '' || !is_numeric($price)) {
  json_out(400, ['ok'=>false, 'message'=>'Missing required fields']);
}

try {
  $sql = "UPDATE products
          SET name=:name, sku=:sku, price=:price,
              date_added=NULLIF(:date_added,''), categories=:categories,
              short_description=:desc, specs=:specs,
              image_path = CASE WHEN :image='' THEN image_path ELSE :image END
          WHERE id=:id";
  $st = db()->prepare($sql);
  $st->execute([
    ':name'=>$name, ':sku'=>$sku, ':price'=>$price,
    ':date_added'=>$date_added, ':categories'=>$categories,
    ':desc'=>$desc, ':specs'=>$specs, ':image'=>$image, ':id'=>$id
  ]);
  json_out(200, ['ok'=>true]);
} catch (Throwable $e) {
  json_out(400, ['ok'=>false, 'message'=>$e->getMessage()]);
}
