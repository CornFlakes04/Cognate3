<?php
// Start the session to keep the user logged in.
session_start();

// Database connection details.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ( !isset($_POST['username'], $_POST['password']) ) {
    exit('Please fill both the username and password fields!');
}

$stmt = $conn->prepare('SELECT id, password, last_login FROM users WHERE username = ?');
$stmt->bind_param('s', $_POST['username']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashed_password, $last_login);
    $stmt->fetch();
    
    if (password_verify($_POST['password'], $hashed_password)) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        $_SESSION['last_login'] = $last_login;

        $stmt_time = $conn->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
        $stmt_time->bind_param('i', $id);
        $stmt_time->execute();
        $stmt_time->close();
        
        header('Location: dashboard.php');
        exit;
    } else {
        // Incorrect password, redirect back to login page with an error.
        header('Location: login.php?error=1');
        exit;
    }
} else {
    // Incorrect username, redirect back to login page with an error.
    header('Location: login.php?error=1');
    exit;
}

$stmt->close();
?>