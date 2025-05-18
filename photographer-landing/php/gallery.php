<?php
header('Content-Type: application/json');
require_once 'config.php';
require_once 'auth.php';

// Проверка авторизации
checkAdminAuth();

// Разрешенные методы
$allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
if (!in_array($_SERVER['REQUEST_METHOD'], $allowedMethods)) {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешен']);
    exit;
}

// Обработка разных методов
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handleGetRequest();
        break;
    case 'POST':
        handlePostRequest();
        break;
    case 'PUT':
        handlePutRequest();
        break;
    case 'DELETE':
        handleDeleteRequest();
        break;
}

function handleGetRequest() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'photos' => $photos]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка при получении фотографий: ' . $e->getMessage()]);
    }
}

function handlePostRequest() {
    // Уже реализовано в upload.php
    http_response_code(405);
    echo json_encode(['error' => 'Используйте /php/upload.php для загрузки файлов']);
}

function handlePutRequest() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['id']) || !isset($data['category'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Неверные параметры запроса']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE gallery SET category = ? WHERE id = ?");
        $stmt->execute([$data['category'], $data['id']]);
        
        echo json_encode(['success' => $stmt->rowCount() > 0]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка при обновлении категории: ' . $e->getMessage()]);
    }
}

function handleDeleteRequest() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Неверные параметры запроса']);
        return;
    }
    
    try {
        // Сначала получаем информацию о фото
        $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt->execute([$data['id']]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$photo) {
            http_response_code(404);
            echo json_encode(['error' => 'Фото не найдено']);
            return;
        }
        
        // Удаляем запись из БД
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$data['id']]);
        
        // Удаляем файл
        $filePath = __DIR__ . '/../images/photos/' . $photo['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка при удалении фото: ' . $e->getMessage()]);
    }
}
?>