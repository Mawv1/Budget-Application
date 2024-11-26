document.addEventListener('DOMContentLoaded', () => {
    const createAccountButton = document.getElementById('create-account');
    if (createAccountButton) {
        createAccountButton.addEventListener('click', (event) => {
            event.preventDefault();
            window.location.href = 'register.php';
        });
    }

    const slides = document.querySelectorAll('.slide active');
    let currentIndex = 0;

    function showNextSlide() {
        slides[currentIndex].classList.remove(' active');
        currentIndex = (currentIndex + 1) % slides.length;
        slides[currentIndex].classList.add(' active');
    }

    setInterval(showNextSlide, 5000);
});
