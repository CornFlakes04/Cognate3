<?php
// /Cognate3/api/_bootstrap.php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function respond(array $arr, int $code = 200): void {
  http_response_code($code);
  echo json_encode($arr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  exit;
}

// ==== DB CONFIG (edit for your env) ====
const DB_HOST    = '127.0.0.1';
const DB_NAME    = 'company_db';
const DB_USER    = 'root';
const DB_PASS    = '';        // <- your password here
const DB_CHARSET = 'utf8mb4';

function pdo(): PDO {
  static $pdo = null;
  if ($pdo instanceof PDO) return $pdo;

  $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
  $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];
  $pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
  return $pdo;
}

// ==== Request helpers ====
function body_json(): array {
  $raw = file_get_contents('php://input') ?: '';
  $j = json_decode($raw, true);
  return is_array($j) ? $j : [];
}

function str_or_null($v): ?string {
  $v = trim((string)$v);
  return $v === '' ? null : $v;
}

// ==== Schema helpers (single copy, cached, guarded) ====
if (!function_exists('table_columns')) {
  function table_columns(string $table): array {
    static $cache = [];
    if (isset($cache[$table])) return $cache[$table];
    $stmt = pdo()->prepare('SHOW COLUMNS FROM `'.$table.'`');
    $stmt->execute();
    // Using FETCH_ASSOC due to default set above.
    $cols = array_map(fn($r) => $r['Field'], $stmt->fetchAll());
    return $cache[$table] = array_flip($cols); // faster key lookup
  }
}

if (!function_exists('has_col')) {
  function has_col(string $table, string $col): bool {
    $cols = table_columns($table);
    return isset($cols[$col]);
  }
}
