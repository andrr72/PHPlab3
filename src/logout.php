<?php
require_once __DIR__ . '/db.php';

$token = $_COOKIE['session_token'] ?? null;
if ($token) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('UPDATE users SET session_token = NULL WHERE session_token = ?');
    $stmt->execute([$token]);
}

// Clear cookies
setcookie('session_token', '', time() - 3600, '/');
setcookie('bg_color', '', time() - 3600, '/');
setcookie('font_color', '', time() - 3600, '/');

header('Location: /login.php');
exit;
