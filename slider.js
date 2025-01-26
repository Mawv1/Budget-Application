document.addEventListener('DOMContentLoaded', () => {
    let counter = 1;
    const totalSlides = document.querySelectorAll('.slides input[type="radio"]').length;

    // Automatyczne przełączanie slajdów co 5 sekund
    setInterval(() => {
        document.getElementById('radio' + counter).checked = true;
        counter++;
        if (counter > totalSlides) {
            counter = 1;
        }
    }, 5000);

    // Renderowanie wykresów dla każdego budżetu
    const favoriteBudgets = document.querySelectorAll('.slide');
    favoriteBudgets.forEach((slide, index) => {
        // Dane wydatków
        const transactionsByExpense = window[`transactionsByCategory${index}`];
        if (transactionsByExpense) {
            const expenseLabels = transactionsByExpense.map(item => item.Category_name || 'Brak kategorii');
            const expenseData = transactionsByExpense.map(item => parseFloat(item.Amount) || 0);
            const ctxExpense = document.getElementById(`chart-expense-${index}`);
            if (ctxExpense) {
                new Chart(ctxExpense.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: expenseLabels,
                        datasets: [{
                            label: 'Wydatki według kategorii',
                            data: expenseData,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        }]
                    },
                });
            }
        }
    
        // Dane przychodów
        const transactionsByIncome = window[`transactionsByCategory1${index}`];
        if (transactionsByIncome) {
            const incomeLabels = transactionsByIncome.map(item => item.Category_name || 'Brak kategorii');
            const incomeData = transactionsByIncome.map(item => parseFloat(item.Amount) || 0);
            const ctxIncome = document.getElementById(`chart-income-${index}`);
            if (ctxIncome) {
                new Chart(ctxIncome.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: incomeLabels,
                        datasets: [{
                            label: 'Przychody według kategorii',
                            data: incomeData,
                            backgroundColor: ['#4BC0C0', '#FFCE56', '#36A2EB', '#9966FF', '#FF6384'],
                        }]
                    },
                });
            }
        }
    });    
});
