<?php
$dsn    = "mysql:host=localhost;dbname=chat_db;charset=utf8mb4";
$dbUser = "root";
$dbPass = "password";


try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


?>