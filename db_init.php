<?php
$dsn    = "mysql://mysql:password@test-1_test:3306/chat_db";
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