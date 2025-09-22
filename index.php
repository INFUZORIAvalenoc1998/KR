<?php
require_once 'config.php';
require_once 'includes/header.php';

// Запрос для получения всех транзакций
$sql = "SELECT * FROM transactions ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Расчет статистики
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

<!-- Заголовок -->
<div class="header">
    <h1><i class="fas fa-wallet"></i> Учет личных финансов</h1>
    <p>Управляйте вашими доходами и расходами в одном месте</p>
</div>

<!-- Статистика -->
<div class="stats-grid">
    <div class="stat-card income">
        <div class="stat-title"><i class="fas fa-arrow-up"></i> Общие доходы</div>
        <div class="stat-value">+ <?= number_format($total_income, 2, '.', ' ') ?> ₽</div>
        <div class="stat-desc">Все полученные средства</div>
    </div>
    
    <div class="stat-card expense">
        <div class="stat-title"><i class="fas fa-arrow-down"></i> Общие расходы</div>
        <div class="stat-value">- <?= number_format($total_expense, 2, '.', ' ') ?> ₽</div>
        <div class="stat-desc">Все потраченные средства</div>
    </div>
    
    <div class="stat-card balance">
        <div class="stat-title"><i class="fas fa-balance-scale"></i> Текущий баланс</div>
        <div class="stat-value"><?= number_format($balance, 2, '.', ' ') ?> ₽</div>
        <div class="stat-desc">Ваш финансовый результат</div>
    </div>
</div>

<!-- Кнопка добавления -->
<div style="text-align: center; margin-bottom: 2rem;">
    <a href="add.php" class="btn-custom btn-primary">
        <i class="fas fa-plus"></i> Добавить операцию
    </a>
</div>

<!-- Таблица операций -->
<div class="transactions-table">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> История операций</h3>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Описание</th>
                    <th>Сумма</th>
                    <th>Тип</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>Операций пока нет</h3>
                            <p>Добавьте первую операцию, чтобы начать учет</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['id']) ?></td>
                        <td><?= htmlspecialchars($transaction['title']) ?></td>
                        <td class="<?= $transaction['type'] == 'доход' ? 'text-success' : 'text-danger' ?>">
                            <strong><?= number_format($transaction['amount'], 2, '.', ' ') ?> ₽</strong>
                        </td>
                        <td>
                            <span class="badge <?= $transaction['type'] == 'доход' ? 'badge-income' : 'badge-expense' ?>">
                                <i class="fas fa-<?= $transaction['type'] == 'доход' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                <?= htmlspecialchars($transaction['type']) ?>
                            </span>
                        </td>
                        <td><?= date('d.m.Y H:i', strtotime($transaction['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="edit.php?id=<?= $transaction['id'] ?>" class="btn-custom btn-warning" style="padding: 0.5rem 1rem;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete.php?id=<?= $transaction['id'] ?>" class="btn-custom btn-danger" style="padding: 0.5rem 1rem;" onclick="return confirm('Удалить эту операцию?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>