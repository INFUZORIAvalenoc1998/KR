<?php
// config.php - ИСПРАВЛЕННАЯ ВЕРСИЯ
session_start();

// Создаем папку для базы данных если её нет
if (!is_dir('database')) {
    mkdir('database', 0777, true);
}

try {
    // Пробуем подключиться к MySQL
    $pdo = new PDO("mysql:host=localhost;dbname=finance_manager;charset=utf8", "GenZ", "ROOT");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Подключение к MySQL успешно!";
    
} catch (PDOException $e) {
    // Если MySQL не работает, используем SQLite
    try {
        echo "⚠️ MySQL недоступен, используем SQLite...<br>";
        
        $pdo = new PDO('sqlite:database/finance.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Создаем таблицу для SQLite
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS transactions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(255) NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                type TEXT CHECK( type IN ('доход','расход') ) NOT NULL DEFAULT 'расход',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        echo "✅ SQLite подключен и готов к работе!";
        
    } catch (Exception $sqlite_error) {
        die("❌ Оба варианта не работают: " . $sqlite_error->getMessage());
    }
}
?>