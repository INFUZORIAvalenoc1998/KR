// js/script.js
document.addEventListener('DOMContentLoaded', function() {
    // Анимация появления элементов
    const animateElements = () => {
        const elements = document.querySelectorAll('.stat-card, .transactions-table');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
            el.classList.add('fade-in');
        });
    };

    // Подтверждение удаления с кастомным диалогом
    const confirmDelete = (event) => {
        event.preventDefault();
        const url = event.target.href;
        
        if (confirm('Вы уверены, что хотите удалить эту операцию? Это действие нельзя отменить.')) {
            window.location.href = url;
        }
    };

    // Динамическое обновление суммы при вводе
    const formatAmountInput = () => {
        const amountInput = document.getElementById('amount');
        if (amountInput) {
            amountInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d.]/g, '');
                e.target.value = value;
            });
        }
    };

    // Подсветка строк таблицы при наведении
    const enhanceTableInteractivity = () => {
        const tableRows = document.querySelectorAll('.table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });
    };

    // Инициализация всех функций
    const init = () => {
        animateElements();
        formatAmountInput();
        enhanceTableInteractivity();
        
        // Назначение обработчиков для кнопок удаления
        document.querySelectorAll('a[href*="delete.php"]').forEach(link => {
            link.addEventListener('click', confirmDelete);
        });
    };

    init();
});