<?php
// Cognate3/api/products/list.php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php'; // provides $pdo, respond()

// Optional auth gate:
// session_start();
// if (empty($_SESSION['user_id'])) respond(401, ['message' => 'Unauthorized']);

$debug = isset($_GET['debug']) && $_GET['debug'] == '1';

try {
  // 1) Discover existing columns so we only reference real ones.
  $desc = $pdo->query('DESCRIBE products')->fetchAll();
  $existing = [];
  foreach ($desc as $col) {
    $existing[strtolower($col['Field'])] = true;
  }
  $has = fn(string $c) => isset($existing[strtolower($c)]);

  // Helper: pick first existing column from a list; else return empty string literal
  $pick = function(array $candidates): string {
    global $has;
    foreach ($candidates as $c) {
      if ($has($c)) return 'p.`' . $c . '`';
    }
    return "''";
  };

  // 2) Build SELECT parts safely
  $idExpr    = $has('id')      ? 'p.`id`'      : '0';
  $nameExpr  = $pick(['name', 'title']);
  $skuExpr   = $pick(['sku', 'code']);
  $priceExpr = $pick(['price', 'amount']);
  $descExpr  = $pick(['short_description', 'description', 'desc', 'details']);
  $catsExpr  = $pick(['categories', 'category', 'cats', 'tags']);
  $specsExpr = $pick(['specs', 'features']);
  $dateExpr  = $pick(['date_added', 'created_at', 'created', 'added_on']);

  $imageExpr = $pick(['image_url', 'image_path', 'image', 'photo', 'thumbnail']);
  $videoExpr = $pick(['video_url', 'video', 'vid']);

  // 3) Compose SQL using only safe expressions
  $sql = "
    SELECT
      {$idExpr}   AS id,
      {$nameExpr} AS name,
      {$skuExpr}  AS sku,
      {$priceExpr} AS price,
      {$descExpr} AS description,
      {$catsExpr} AS categories,
      {$specsExpr} AS specs,
      {$dateExpr} AS date_added,
      {$imageExpr} AS image,
      {$videoExpr} AS video_url
    FROM products p
    ORDER BY " . ($has('id') ? 'p.`id`' : '1') . " DESC
  ";

  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll();

  // 4) Normalize for frontend
  $items = array_map(function ($r) {
    return [
      'id'          => isset($r['id']) ? (int)$r['id'] : 0,
      'name'        => (string)($r['name'] ?? ''),
      'sku'         => (string)($r['sku'] ?? ''),
      'price'       => isset($r['price']) ? (float)$r['price'] : 0.0,
      'description' => (string)($r['description'] ?? ''),
      'categories'  => (string)($r['categories'] ?? ''),
      'specs'       => (string)($r['specs'] ?? ''),
      'date_added'  => (string)($r['date_added'] ?? ''),
      'image'       => (string)($r['image'] ?? ''),
      'video_url'   => (string)($r['video_url'] ?? ''),
    ];
  }, $rows);

  respond(200, ['items' => $items]);
} catch (Throwable $e) {
  error_log('[products.list] ' . $e->getMessage());
  if ($debug) {
    respond(500, ['message' => 'List failed', 'error' => $e->getMessage()]);
  }
  respond(500, ['message' => 'List failed']);
}
