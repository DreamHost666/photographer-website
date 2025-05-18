<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Простая проверка (в реальном проекте используйте хеширование паролей)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.html');
        exit;
    } else {
        header('Location: login.html?error=1');
        exit;
    }
}

// Проверка авторизации для защищенных страниц
function checkAdminAuth() {
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        header('Location: login.html');
        exit;
    }
}
?>