<?php
require_once 'config.php';

// Проверяем, передан ли ID операции
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$error = '';

// Получаем данные операции для предзаполнения формы
$sql = "SELECT * FROM transactions WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

// Если операция не найдена
if (!$transaction) {
    header('Location: index.php');
    exit;
}

$title = $transaction['title'];
$amount = $transaction['amount'];
$type = $transaction['type'];

// Обработка данных формы после отправки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $amount = trim($_POST['amount']);
    $type = $_POST['type'];

    // Валидация
    if (empty($title) || empty($amount)) {
        $error = 'Пожалуйста, заполните все обязательные поля.';
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = 'Сумма должна быть положительным числом.';
    } else {
        // Если ошибок нет, обновляем запись в БД
        $sql = "UPDATE transactions SET title = :title, amount = :amount, type = :type WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':amount' => $amount,
            ':type' => $type,
            ':id' => $id
        ]);

        // Перенаправляем на главную страницу
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать операцию</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1>Редактировать операцию</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Название операции *</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Сумма (руб.) *</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?= htmlspecialchars($amount) ?>" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Тип операции *</label>
            <select class="form-select" id="type" name="type" required>
                <option value="расход" <?= $type == 'расход' ? 'selected' : '' ?>>Расход</option>
                <option value="доход" <?= $type == 'доход' ? 'selected' : '' ?>>Доход</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="index.php" class="btn btn-secondary">Отмена</a>
    </form>
</body>
</html>