<?php
require __DIR__ . '/../db.php';

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$id = (int)($input['id'] ?? 0);
if ($id <= 0) json_out(400, ['ok'=>false, 'message'=>'Invalid id']);

try {
  // Optional: also remove the file from disk. We’ll fetch the path first.
  $st = db()->prepare("SELECT image_path FROM products WHERE id=:id");
  $st->execute([':id'=>$id]);
  $row = $st->fetch();

  $del = db()->prepare("DELETE FROM products WHERE id=:id");
  $del->execute([':id'=>$id]);

  if ($row && !empty($row['image_path'])) {
    // Convert public URL path (/Cognate3/uploads/...) to filesystem path
    $rel = preg_replace('#^/Cognate3/#', '', $row['image_path']);
    $file = realpath(__DIR__ . '/..' . DIRECTORY_SEPARATOR . $rel);
    // Only unlink if it’s inside our uploads dir
    $uploadsDir = realpath(__DIR__ . '/..' . DIRECTORY_SEPARATOR . 'uploads');
    if ($file && $uploadsDir && str_starts_with($file, $uploadsDir) && is_file($file)) {
      @unlink($file);
    }
  }

  json_out(200, ['ok'=>true]);
} catch (Throwable $e) {
  json_out(500, ['ok'=>false, 'message'=>$e->getMessage()]);
}
