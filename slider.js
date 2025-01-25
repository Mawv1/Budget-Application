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
        const transactionsByCategory = window[`transactionsByCategory${index}`];
        const transactionsByDate = window[`transactionsByDate${index}`];

        if (transactionsByCategory) {
            const categoryLabels = transactionsByCategory.map(item => item.Category_name || 'Brak kategorii');
            const categoryData = transactionsByCategory.map(item => parseFloat(item.Amount) || 0);
            const ctxCategory = document.getElementById(`chart-category-${index}`);
            if (ctxCategory) {
                new Chart(ctxCategory.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            label: 'Wydatki według kategorii',
                            data: categoryData,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        }]
                    },
                });
            }
        }

        if (transactionsByDate) {
            const dateLabels = transactionsByDate.map(item => item.Date || 'Brak daty');
            const dateData = transactionsByDate.map(item => parseFloat(item.Amount) || 0);
            const ctxDate = document.getElementById(`chart-date-${index}`);
            if (ctxDate) {
                new Chart(ctxDate.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: dateLabels,
                        datasets: [{
                            label: 'Wydatki w czasie',
                            data: dateData,
                            borderColor: '#36A2EB',
                            fill: false,
                        }]
                    },
                });
            }
        }
    });
});
