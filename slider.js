document.addEventListener('DOMContentLoaded', () => {
    let counter = 1;
    const totalSlides = document.querySelectorAll('.slides input[type="radio"]').length;

    setInterval(() => {
        document.getElementById('radio' + counter).checked = true;
        counter++;
        if (counter > totalSlides) {
            counter = 1;
        }
    }, 5000); // Przełączanie co 5 sekund
});
