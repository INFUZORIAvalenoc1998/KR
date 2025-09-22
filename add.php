<?php
require_once 'config.php';
require_once 'includes/header.php';

$title = '';
$amount = '';
$type = 'расход';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $amount = trim($_POST['amount']);
    $type = $_POST['type'];

    if (empty($title) || empty($amount)) {
        $error = 'Пожалуйста, заполните все обязательные поля.';
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = 'Сумма должна быть положительным числом.';
    } else {
        $sql = "INSERT INTO transactions (title, amount, type) VALUES (:title, :amount, :type)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':amount' => $amount,
            ':type' => $type
        ]);

        header('Location: index.php');
        exit;
    }
}
?>

<div class="form-container">
    <h2 style="margin-bottom: 2rem; text-align: center;">
        <i class="fas fa-plus-circle"></i> Добавить операцию
    </h2>

    <?php if ($error): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label class="form-label" for="title">
                <i class="fas fa-pencil-alt"></i> Название операции *
            </label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($title) ?>" 
                   placeholder="Например: Зарплата, Продукты, Бензин..." required>
        </div>

        <div class="form-group">
            <label class="form-label" for="amount">
                <i class="fas fa-ruble-sign"></i> Сумма (руб.) *
            </label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                   value="<?= htmlspecialchars($amount) ?>" placeholder="0.00" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="type">
                <i class="fas fa-exchange-alt"></i> Тип операции *
            </label>
            <select class="form-select" id="type" name="type" required>
                <option value="расход" <?= $type == 'расход' ? 'selected' : '' ?>>📉 Расход</option>
                <option value="доход" <?= $type == 'доход' ? 'selected' : '' ?>>📈 Доход</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-custom btn-primary" style="flex: 1;">
                <i class="fas fa-check"></i> Добавить операцию
            </button>
            <a href="index.php" class="btn-custom" style="flex: 1; background: #6c757d; color: white; text-align: center;">
                <i class="fas fa-times"></i> Отмена
            </a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>