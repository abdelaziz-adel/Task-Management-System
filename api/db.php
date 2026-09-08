<?php
$host = "mysql-d329361-zezo-0934.d.aivencloud.com";
$port = "17215";
$dbname = "defaultdb";
$username = "avnadmin";
$password = "AVNS_Uv6VSiCtDxsnWrVz7UV";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/ca.pem',
        ]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

function maskEmail($email) {
    $parts = explode('@', $email);
    if (count($parts) !== 2) return $email;
    $visible = mb_substr($parts[0], 0, 2);
    return $visible . '****@' . $parts[1];
}