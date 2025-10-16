<?php
// authenticate.php
// No output before headers.

session_start();

// --- DB connection (adjust if needed) ---
$mysqli = new mysqli('localhost', 'root', '', 'company_db');
if ($mysqli->connect_error) {
  // DB problem → back to login with error flag
  header('Location: login.html?error=db');
  exit;
}

// --- Validate input from login.html ---
if (empty($_POST['username']) || empty($_POST['password'])) {
  header('Location: login.html?error=2'); // "Please fill in both fields."
  exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

// --- Fetch user by username ---
$stmt = $mysqli->prepare('SELECT id, username, password, last_login FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$res  = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

// --- Check password ---
if ($user && password_verify($password, $user['password'])) {
  // Rotate session ID to prevent fixation
  session_regenerate_id(true);

  $_SESSION['loggedin']   = true;
  $_SESSION['id']         = (int)$user['id'];
  $_SESSION['name']       = $user['username'];
  $_SESSION['last_login'] = $user['last_login'];

  // Optional: simple "remember me" if your login.html has a checkbox named "remember"
  if (!empty($_POST['remember'])) {
    // Basic demo cookie (username). For production, use a persistent token table.
    setcookie(
      'nx_remember',
      $user['username'],
      time() + 60 * 60 * 24 * 30, // 30 days
      '/',                        // path
      '',                         // domain (default)
      false,                      // secure (set true on HTTPS)
      true                        // httponly
    );
  }

  // Update last_login timestamp (ignore errors silently)
  $stmt2 = $mysqli->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
  if ($stmt2) {
    $stmt2->bind_param('i', $user['id']);
    $stmt2->execute();
    $stmt2->close();
  }

  $mysqli->close();

  // Redirect after successful login
  // Use index.php if your homepage needs to read the session; index.html is fine if it's public.
  header('Location: index.php');
  exit;
}

// --- Fail: bad username or password ---
$mysqli->close();
header('Location: login.html?error=1'); // "Incorrect username or password."
exit;
