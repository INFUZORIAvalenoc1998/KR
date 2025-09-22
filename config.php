<?php
// config.php - версия с SQLite (рекомендуется)
try {
    // Используем SQLite вместо MySQL
    $pdo = new PDO('sqlite:database/finance.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создаем таблицу если её нет
    $pdo->exec("CREATE TABLE IF NOT EXISTS transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(255) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        type TEXT CHECK( type IN ('доход','расход') ) NOT NULL DEFAULT 'расход',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Создаем папку для базы данных если её нет
if (!is_dir('database')) {
    mkdir('database', 0777, true);
}
?>