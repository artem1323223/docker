<?php
session_start();

// Настройки базы данных
define('DB_HOST', 'mysql');
define('DB_NAME', 'apt_db');
define('DB_USER', 'apt_user');
define('DB_PASSWORD', 'apt123');

// Настройки сайта
define('SITE_NAME', 'АПТ Техникум');
define('SITE_URL', 'http://localhost:8080');
define('UPLOAD_DIR', __DIR__ . '/uploads/avatars/');

// Установка кодировки
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Функция подключения к БД
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASSWORD,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
            );
        } catch(PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Функция проверки авторизации
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Функция получения текущего пользователя
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Функция проверки роли
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['user_role'] === 'admin';
}

// Функция для безопасного вывода
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
