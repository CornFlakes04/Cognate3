<?php
require __DIR__ . '/db.php';

// Where to store files (relative to htdocs)
$baseUrlPrefix = '/Cognate3';             // your site base folder
$uploadDir     = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR . 'uploads';

if (!is_dir($uploadDir)) {
  @mkdir($uploadDir, 0775, true);
}

if (!isset($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
  json_out(400, ['ok' => false, 'message' => 'No image uploaded']);
}

$f = $_FILES['image'];
if ($f['error'] !== UPLOAD_ERR_OK) {
  json_out(400, ['ok' => false, 'message' => 'Upload error code ' . $f['error']]);
}

// Validate size (<= 10MB)
if ($f['size'] > 10 * 1024 * 1024) {
  json_out(413, ['ok' => false, 'message' => 'Image too large (max 10MB)']);
}

// Validate MIME using finfo
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($f['tmp_name']);
$map = [
  'image/png'  => 'png',
  'image/jpeg' => 'jpg',
  'image/gif'  => 'gif',
  'image/webp' => 'webp',
];
if (!isset($map[$mime])) {
  json_out(400, ['ok' => false, 'message' => 'Unsupported image type']);
}

$ext = $map[$mime];
// Generate unique filename
$fname = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$dest  = $uploadDir . DIRECTORY_SEPARATOR . $fname;

if (!move_uploaded_file($f['tmp_name'], $dest)) {
  json_out(500, ['ok' => false, 'message' => 'Failed to move uploaded file']);
}

// Return a path that the browser can use. We keep only the relative path in DB.
$publicPath = $baseUrlPrefix . '/uploads/' . $fname;

json_out(200, ['ok' => true, 'path' => $publicPath]);
