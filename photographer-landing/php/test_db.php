<?php
require_once 'config.php';

try {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Подключение к базе данных успешно!";
} catch(PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>