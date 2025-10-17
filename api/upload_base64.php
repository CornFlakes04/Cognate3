<?php
// /Cognate3/api/upload_base64.php
declare(strict_types=1);

require_once __DIR__ . '/_db.php'; // only for consistent headers

header('Content-Type: application/json; charset=utf-8');

try {
  $data = json_input();
  if (!$data) throw new Exception('Invalid JSON');

  $b64 = $data['data'] ?? '';
  $filename = $data['filename'] ?? 'image';
  if (!$b64) throw new Exception('Missing data');

  // Accept both dataURL and raw base64
  $mime = $data['mime'] ?? 'image/jpeg';
  if (preg_match('/^data:(.+?);base64,(.+)$/', $b64, $m)) {
    $mime = $m[1];
    $b64  = $m[2];
  }
  $bin = base64_decode($b64, true);
  if ($bin === false) throw new Exception('Base64 decode failed');

  $ext = match ($mime) {
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
    default      => 'jpg'
  };

  // Ensure uploads dir exists
  $root = realpath(__DIR__ . '/..'); // /Cognate3
  $uploads = $root . DIRECTORY_SEPARATOR . 'uploads';
  if (!is_dir($uploads)) {
    if (!mkdir($uploads, 0777, true) && !is_dir($uploads)) {
      throw new Exception('Failed to create uploads directory');
    }
  }

  // Write file
  $base = preg_replace('/[^A-Za-z0-9._-]+/', '-', pathinfo($filename, PATHINFO_FILENAME)) ?: 'img';
  $dest = $uploads . DIRECTORY_SEPARATOR . $base . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
  if (file_put_contents($dest, $bin) === false) {
    throw new Exception('Failed to write file');
  }

  // Public path to return
  $publicPath = '/Cognate3/uploads/' . basename($dest);
  respond(200, ['ok' => true, 'path' => $publicPath]);
} catch (Throwable $e) {
  respond(400, ['ok' => false, 'message' => $e->getMessage()]);
}
