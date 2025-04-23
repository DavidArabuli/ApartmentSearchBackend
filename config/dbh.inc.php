<?php

$host = 'localhost';
$dbname = 'ssparsing';
$username = 'root';
$password = 'qwerty';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // var_dump($pdo);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");


    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "MySQL connection failed " . $e->getMessage();
}
