document.addEventListener('DOMContentLoaded', () => {
    const createAccountButton = document.getElementById('create-account');

    createAccountButton.addEventListener('click', (event) => {
        event.preventDefault(); // Zapobiega domyślnemu zachowaniu linku
        window.location.href = 'register.html'; // Przekierowanie na formularz
    });
});
