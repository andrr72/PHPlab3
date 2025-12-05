<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $bg = $_POST['bg_color'] ?? '#ffffff';
    $font = $_POST['font_color'] ?? '#000000';

    if ($username === '' || $password === '') {
        http_response_code(400);
        echo 'username and password required';
        exit;
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo 'user exists';
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, bg_color, font_color, session_token) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$username, $hash, $bg, $font, $token]);

    // Set cookies: session_token (persistent), settings (optional)
    setcookie('session_token', $token, time() + 60*60*24*30, '/');
    setcookie('bg_color', $bg, time() + 60*60*24*30, '/');
    setcookie('font_color', $font, time() + 60*60*24*30, '/');

    header('Location: /');
    exit;
}

// Simple form for registration
?><!doctype html>
<html><body>
<h2>Register</h2>
<form method="POST">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input type="password" name="password"></label><br>
  <label>Background color: <input name="bg_color" value="#ffffff"></label><br>
  <label>Font color: <input name="font_color" value="#000000"></label><br>
  <button type="submit">Register</button>
</form>
<a href="/login.php">Login</a>
</body></html>
