<?php
// Cognate3/api/health.php
declare(strict_types=1);
header('Content-Type: application/json');

require_once __DIR__ . '/_db.php'; // if this throws, you'll get a 500 and probe will fail

echo json_encode(['ok' => true, 'message' => 'API is alive']);
