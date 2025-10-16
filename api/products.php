<?php
declare(strict_types=1);

require __DIR__ . '/_auth.php';  // starts session, has $pdo + respond()
require_admin();

// Uncomment if different origin:
// header('Access-Control-Allow-Origin: http://localhost');
// header('Access-Control-Allow-Credentials: true');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$method = $_SERVER['REQUEST_METHOD'];

try {
  if ($method === 'GET') {
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    if ($q !== '') {
      $like = "%$q%";
      $stmt = $pdo->prepare(
        "SELECT * FROM products
         WHERE name LIKE ? OR sku LIKE ? OR categories LIKE ? OR description LIKE ?
         ORDER BY id DESC"
      );
      $stmt->execute([$like,$like,$like,$like]);
    } else {
      $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    }
    respond(200, $stmt->fetchAll());
  }

  if ($method === 'POST') {
    $b = json_input();
    $stmt = $pdo->prepare(
      "INSERT INTO products (name, sku, price, categories, added, description, specs, image_url, video_url)
       VALUES (:name,:sku,:price,:categories,:added,:description,:specs,:image_url,:video_url)"
    );
    $stmt->execute([
      ':name'        => (string)($b['name'] ?? ''),
      ':sku'         => (string)($b['sku'] ?? ''),
      ':price'       => (float)($b['price'] ?? 0),
      ':categories'  => (string)($b['categories'] ?? ''),
      ':added'       => ($b['added'] ?? null) ?: null,
      ':description' => (string)($b['description'] ?? ''),
      ':specs'       => ($b['specs'] ?? null),
      ':image_url'   => ($b['image_url'] ?? null),
      ':video_url'   => ($b['video_url'] ?? null),
    ]);
    respond(200, ['ok' => true, 'id' => $pdo->lastInsertId()]);
  }

  if ($method === 'PUT') {
    $b = json_input();
    $id = (int)($b['id'] ?? 0);
    if ($id <= 0) respond(400, ['message' => 'Missing id']);

    $stmt = $pdo->prepare(
      "UPDATE products SET
        name=:name, sku=:sku, price=:price, categories=:categories, added=:added,
        description=:description, specs=:specs, image_url=:image_url, video_url=:video_url
       WHERE id=:id"
    );
    $stmt->execute([
      ':name'        => (string)($b['name'] ?? ''),
      ':sku'         => (string)($b['sku'] ?? ''),
      ':price'       => (float)($b['price'] ?? 0),
      ':categories'  => (string)($b['categories'] ?? ''),
      ':added'       => ($b['added'] ?? null) ?: null,
      ':description' => (string)($b['description'] ?? ''),
      ':specs'       => ($b['specs'] ?? null),
      ':image_url'   => ($b['image_url'] ?? null),
      ':video_url'   => ($b['video_url'] ?? null),
      ':id'          => $id,
    ]);
    respond(200, ['ok' => true]);
  }

  if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) respond(400, ['message' => 'Missing id']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
    respond(200, ['ok' => true]);
  }

  respond(405, ['message' => 'Method not allowed']);
} catch (Throwable $e) {
  respond(500, ['message' => 'Server error', 'details' => $e->getMessage()]);
}
