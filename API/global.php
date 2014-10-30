<?php
require_once "config.php";

try {
    $dsn = 'mysql:dbname=' . $dbname . ';host=' . $host;
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

function __autoload($class_name) {
    include "classes/" . $class_name . '.php';
}

$Database = Database::singleton($pdo);