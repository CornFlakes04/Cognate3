<?php
// /Cognate3/api/health.php
require __DIR__.'/_bootstrap.php';

try {
  pdo()->query('SELECT 1');
  respond(['ok' => true, 'db' => 'up']);
} catch (Throwable $e) {
  respond(['ok' => false, 'db' => 'down', 'error' => $e->getMessage()], 500);
}
