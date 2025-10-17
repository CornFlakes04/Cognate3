<?php
// Adjust if your MySQL creds differ
const DB_DSN  = 'mysql:host=127.0.0.1;dbname=company_db;charset=utf8mb4';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO {
  static $pdo = null;
  if ($pdo === null) {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  }
  return $pdo;
}

function json_out($code, $data) {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_SLASHES);
  exit;
}
