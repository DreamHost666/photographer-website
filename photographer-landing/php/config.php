<?php
// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_USER', 'photo_user'); // или 'root' если используете root
define('DB_PASS', 'your_password_here');
define('DB_NAME', 'photographer_db');

// Настройки загрузки файлов
define('UPLOAD_DIR', __DIR__ . '/../images/photos/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Подключение к базе данных
try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>