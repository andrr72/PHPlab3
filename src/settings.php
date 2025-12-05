<?php
require_once __DIR__ . '/db.php';

$token = $_COOKIE['session_token'] ?? null;
if (!$token) {
    header('Location: /login.php');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT * FROM users WHERE session_token = ?');
$stmt->execute([$token]);
$user = $stmt->fetch();
if (!$user) {
    header('Location: /login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bg = $_POST['bg_color'] ?? $user['bg_color'];
    $font = $_POST['font_color'] ?? $user['font_color'];
    $stmt = $pdo->prepare('UPDATE users SET bg_color = ?, font_color = ? WHERE id = ?');
    $stmt->execute([$bg, $font, $user['id']]);

    setcookie('bg_color', $bg, time() + 60*60*24*30, '/');
    setcookie('font_color', $font, time() + 60*60*24*30, '/');

    header('Location: /');
    exit;
}

?><!doctype html>
<html><body>
<h2>Settings for <?php echo htmlspecialchars($user['username']); ?></h2>
<form method="POST">
  <label>Background color: <input name="bg_color" value="<?php echo htmlspecialchars($user['bg_color']); ?>"></label><br>
  <label>Font color: <input name="font_color" value="<?php echo htmlspecialchars($user['font_color']); ?>"></label><br>
  <button type="submit">Save</button>
</form>
<a href="/logout.php">Logout</a>
</body></html>
