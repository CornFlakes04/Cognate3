<?php
// /Cognate3/api/health.php
declare(strict_types=1);

header('Content-Type: application/json');

// (optional) CORS if you’re opening the page from another origin/port
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Methods: GET,OPTIONS');
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

echo json_encode(['ok' => true, 'message' => 'API is alive']);
