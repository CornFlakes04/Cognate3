<?php
// /Cognate3/api/auth/logout.php
declare(strict_types=1);

// Strong no-cache, avoid bfcache showing "logged-in" UI after logout
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

session_start();

// Wipe all session data
$_SESSION = [];

// Delete PHPSESSID cookie (and any custom auth cookie you might use)
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', $params['secure'] ?? false, $params['httponly'] ?? true);
}

// Example: if you set any custom cookies like "nexora_auth", clear them too
setcookie('nexora_auth', '', time() - 3600, '/', '', false, true);

// Destroy and regenerate
session_destroy();
session_write_close();
session_id('');
session_regenerate_id(true);

// Redirect to the public homepage (NOT to login)
header('Location: /Cognate3/index.html');
exit;
