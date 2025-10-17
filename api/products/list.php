<?php
require __DIR__ . '/../db.php';

try {
  $sql = "SELECT id, name, sku, price, date_added, categories,
                 short_description, specs, image_path, created_at, updated_at
          FROM products
          ORDER BY id DESC";
  $rows = db()->query($sql)->fetchAll();

  // Normalize for frontend expected keys
  $items = array_map(function($r){
    return [
      'id'          => (int)$r['id'],
      'name'        => $r['name'],
      'sku'         => $r['sku'],
      'price'       => (float)$r['price'],
      'date_added'  => $r['date_added'],
      'categories'  => $r['categories'],
      'description' => $r['short_description'],
      'specs'       => $r['specs'],
      // Frontend expects "image". We pass the stored path; it’s a URL path under /Cognate3/uploads/...
      'image'       => $r['image_path'] ?: ''
    ];
  }, $rows);

  json_out(200, ['ok'=>true, 'items'=>$items]);
} catch (Throwable $e) {
  json_out(500, ['ok'=>false, 'message'=>'List failed']);
}
