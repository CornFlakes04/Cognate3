<?php
// /Cognate3/api/users/list.php
declare(strict_types=1);

require __DIR__ . '/../_bootstrap.php';

try {
  $sql = "SELECT 
            id,
            name AS full_name,
            username,
            email,
            role,
            status,
            last_login,
            created_at,
            updated_at
          FROM users
          WHERE archived = 0
          ORDER BY id DESC";
  $rows = pdo()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  respond(['ok' => true, 'items' => $rows]);
} catch (Throwable $e) {
  respond(['ok' => false, 'error' => $e->getMessage()], 500);
}
