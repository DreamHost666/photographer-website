<?php
header('Content-Type: application/json');
require_once 'config.php';

// Убедимся, что это POST-запрос
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешен']);
    exit;
}

// Проверим авторизацию администратора
session_start();
if (!isset($_SESSION['admin_logged_in']) {
    http_response_code(403);
    echo json_encode(['error' => 'Доступ запрещен']);
    exit;
}

// Директория для загрузки
$uploadDir = __DIR__ . '/../images/photos/';

// Создадим директорию, если её нет
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['error' => 'Не удалось создать директорию для загрузки']);
        exit;
    }
}

// Проверим наличие файла
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Ошибка загрузки файла']);
    exit;
}

// Проверим тип файла
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$fileType = mime_content_type($_FILES['photo']['tmp_name']);
if (!in_array($fileType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['error' => 'Недопустимый тип файла']);
    exit;
}

// Проверим размер файла (макс. 5MB)
$maxSize = 5 * 1024 * 1024;
if ($_FILES['photo']['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'Файл слишком большой (максимум 5MB)']);
    exit;
}

// Генерируем уникальное имя файла
$extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$fileName = uniqid('photo_') . '.' . $extension;
$targetPath = $uploadDir . $fileName;

// Пробуем переместить файл
if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при сохранении файла']);
    exit;
}

// Получаем данные из формы
$category = $_POST['category'] ?? 'other';
$description = $_POST['description'] ?? '';

// Сохраняем информацию в БД
try {
    $stmt = $pdo->prepare("INSERT INTO gallery (image_path, category, description) VALUES (?, ?, ?)");
    $stmt->execute([$fileName, $category, $description]);
    
    echo json_encode([
        'success' => true,
        'image_path' => $fileName,
        'category' => $category,
        'description' => $description,
        'id' => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    // Удаляем файл, если не удалось сохранить в БД
    unlink($targetPath);
    
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>