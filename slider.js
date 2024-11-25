document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.slider');
    const sliderItems = document.querySelectorAll('.slider-item');
    const prevButton = document.querySelector('.slider-nav .prev');
    const nextButton = document.querySelector('.slider-nav .next');
    let currentIndex = 0;

    const chartData = {
        week: {
            labels: ["Pon", "Wt", "Śr", "Czw", "Pt", "Sob", "Nd"],
            values: [120, 200, 150, 80, 170, 90, 110],
        },
        month: {
            labels: Array.from({ length: 30 }, (_, i) => i + 1),
            values: Array.from({ length: 30 }, () => Math.floor(Math.random() * 200)),
        },
        year: {
            labels: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"],
            values: Array.from({ length: 12 }, () => Math.floor(Math.random() * 5000)),
        },
    };

    function createChart(canvasId, labels, dataValues, title) {
        const ctx = document.getElementById(canvasId).getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: title,
                    data: dataValues,
                    borderColor: "rgba(75, 192, 192, 1)",
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderWidth: 2,
                    tension: 0.4,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }

    createChart("chart-week", chartData.week.labels, chartData.week.values, "Wydatki Tygodniowe");
    createChart("chart-month", chartData.month.labels, chartData.month.values, "Wydatki Miesięczne");
    createChart("chart-year", chartData.year.labels, chartData.year.values, "Wydatki Roczne");

    function updateSlider() {
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === sliderItems.length - 1;
    }

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) currentIndex--;
        updateSlider();
    });

    nextButton.addEventListener('click', () => {
        if (currentIndex < sliderItems.length - 1) currentIndex++;
        updateSlider();
    });

    updateSlider();
});
