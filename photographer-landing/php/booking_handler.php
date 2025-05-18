<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name']),
        'phone' => trim($_POST['phone']),
        'email' => trim($_POST['email']),
        'shooting_date' => $_POST['date'],
        'session_type' => $_POST['session-type'],
        'message' => trim($_POST['message'] ?? '')
    ];

    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (name, phone, email, shooting_date, session_type, message) 
                              VALUES (:name, :phone, :email, :shooting_date, :session_type, :message)");
        $stmt->execute($data);
        
        echo json_encode(['success' => true, 'message' => 'Ваша заявка успешно отправлена!']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ошибка при сохранении заявки: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
}
?>