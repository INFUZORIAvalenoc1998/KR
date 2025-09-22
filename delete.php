<?php
require_once 'config.php';

// Проверяем, передан ли ID операции
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Подготовленный запрос для удаления
    $sql = "DELETE FROM transactions WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

// Перенаправляем на главную страницу независимо от результата
header('Location: index.php');
exit;
?>