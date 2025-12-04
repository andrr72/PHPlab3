<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        echo 'invalid';
        exit;
    }

    // Generate new session token and save
    $token = bin2hex(random_bytes(32));
    $stmt = $pdo->prepare('UPDATE users SET session_token = ? WHERE id = ?');
    $stmt->execute([$token, $user['id']]);

    setcookie('session_token', $token, time() + 60*60*24*30, '/');
    setcookie('bg_color', $user['bg_color'], time() + 60*60*24*30, '/');
    setcookie('font_color', $user['font_color'], time() + 60*60*24*30, '/');

    header('Location: /');
    exit;
}

?><!doctype html>
<html><body>
<h2>Login</h2>
<form method="POST">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input type="password" name="password"></label><br>
  <button type="submit">Login</button>
</form>
<a href="/register.php">Register</a>
</body></html>
