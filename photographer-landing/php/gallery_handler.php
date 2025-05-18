<?php
header('Content-Type: application/json');
require_once '../config.php';

// Создаем папку для загрузки, если ее нет
if (!file_exists(UPLOAD_DIR)) {
    if (!mkdir(UPLOAD_DIR, 0755, true)) {
        die(json_encode(['error' => 'Не удалось создать папку для загрузки']));
    }
}

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Метод не поддерживается']));
}

// Проверяем, что файл был загружен
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
    http_response_code(400);
    die(json_encode(['error' => 'Файл не был загружен']));
}

$file = $_FILES['photo'];

// Проверяем ошибки загрузки
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'Размер файла превышает upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'Размер файла превышает MAX_FILE_SIZE',
        UPLOAD_ERR_PARTIAL => 'Файл загружен только частично',
        UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка',
        UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск',
        UPLOAD_ERR_EXTENSION => 'Загрузка остановлена расширением PHP'
    ];
    
    $error = $errorMessages[$file['error'] ?? 'Неизвестная ошибка загрузки';
    http_response_code(400);
    die(json_encode(['error' => $error]));
}

// Проверяем тип файла
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($fileInfo, $file['tmp_name']);
finfo_close($fileInfo);

if (!in_array($mimeType, $allowedTypes)) {
    http_response_code(400);
    die(json_encode(['error' => 'Недопустимый тип файла. Разрешены только JPEG, PNG и GIF']));
}

// Проверяем размер файла
if ($file['size'] > MAX_FILE_SIZE) {
    http_response_code(400);
    die(json_encode(['error' => 'Файл слишком большой. Максимальный размер: 5MB']));
}

// Генерируем уникальное имя файла
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('img_') . '.' . $extension;
$targetPath = UPLOAD_DIR . $filename;

// Перемещаем файл
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    http_response_code(500);
    die(json_encode(['error' => 'Ошибка при сохранении файла']));
}

// Сохраняем информацию в БД
try {
    $stmt = $pdo->prepare("INSERT INTO gallery (image_path, category, description) 
                          VALUES (:image_path, :category, :description)");
    
    $stmt->execute([
        ':image_path' => $filename,
        ':category' => $_POST['category'],
        ':description' => $_POST['description'] ?? ''
    ]);
    
    $imageId = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'id' => $imageId,
        'filename' => $filename,
        'path' => 'images/photos/' . $filename
    ]);
    
} catch (PDOException $e) {
    // Удаляем файл, если не удалось сохранить в БД
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }
    
    http_response_code(500);
    die(json_encode(['error' => 'Ошибка базы данных: ' . $e->getMessage()]));
}
?>