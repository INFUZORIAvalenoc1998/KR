<?php
require_once 'config.php';

// Запрос для получения всех транзакций, отсортированных по дате (сначала новые)
$sql = "SELECT * FROM transactions ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Расчет общего дохода, расхода и баланса
$total_income = 0;
$total_expense = 0;

foreach ($transactions as $transaction) {
    if ($transaction['type'] == 'доход') {
        $total_income += $transaction['amount'];
    } else {
        $total_expense += $transaction['amount'];
    }
}
$balance = $total_income - $total_expense;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Учет личных финансов</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4">Учет личных финансов</h1>

    <!-- Сводная статистика -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Доходы</h5>
                    <p class="card-text h4"><?= number_format($total_income, 2, '.', ' ') ?> руб.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Расходы</h5>
                    <p class="card-text h4"><?= number_format($total_expense, 2, '.', ' ') ?> руб.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Баланс</h5>
                    <p class="card-text h4"><?= number_format($balance, 2, '.', ' ') ?> руб.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Кнопка для добавления новой транзакции -->
    <a href="add.php" class="btn btn-primary mb-3">Добавить операцию</a>

    <!-- Таблица с транзакциями -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Сумма</th>
                <th>Тип</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="6" class="text-center">Нет записей о транзакциях</td>
                </tr>
            <?php else: ?>
                <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['id']) ?></td>
                    <td><?= htmlspecialchars($transaction['title']) ?></td>
                    <td class="<?= $transaction['type'] == 'доход' ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($transaction['amount'], 2, '.', ' ') ?> руб.
                    </td>
                    <td>
                        <span class="badge bg-<?= $transaction['type'] == 'доход' ? 'success' : 'danger' ?>">
                            <?= htmlspecialchars($transaction['type']) ?>
                        </span>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($transaction['created_at'])) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $transaction['id'] ?>" class="btn btn-sm btn-warning">Редактировать</a>
                        <a href="delete.php?id=<?= $transaction['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить эту операцию?')">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>