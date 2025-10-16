<?php
declare(strict_types=1);

session_start();
require __DIR__ . '/_db.php'; // gives $pdo, respond()

function require_admin(): void {
  // admin = the user logged in with username 'admin'
  if (!isset($_SESSION['user']) || ($_SESSION['user'] ?? '') !== 'admin') {
    respond(401, ['message' => 'Unauthorized']);
  }
}
