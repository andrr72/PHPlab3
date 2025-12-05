<?php
// Simple PDO connection helper. Uses environment vars if available (for Docker).
function getPDO(): PDO
{
    $host = getenv('DB_HOST') ?: 'db';
    $db   = getenv('DB_NAME') ?: 'php_Shemetov';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: 'lab2';
    $port = getenv('DB_PORT') ?: '3306';
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    return new PDO($dsn, $user, $pass, $opts);
}
