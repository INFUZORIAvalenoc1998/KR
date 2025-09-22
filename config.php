<?php
// config.php
$host = 'localhost';
$dbname = 'finance_manager';
$username = 'GenZ'; // Укажите вашего пользователя MySQL
$password = 'ROOT';     // Укажите ваш пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Устанавливаем режим ошибок PDO на исключения
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>