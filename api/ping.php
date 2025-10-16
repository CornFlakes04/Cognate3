<?php
// /Cognate3/api/ping.php  (adjust folder to where your api lives)
header('Content-Type: application/json');
http_response_code(200);
echo json_encode(['ok' => true, 'message' => 'API is alive']);
