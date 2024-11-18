document.addEventListener('DOMContentLoaded', () => {
    const logoutLink = document.getElementById('logout');

    logoutLink.addEventListener('click', (event) => {
        event.preventDefault(); // Zapobiega domyślnemu zachowaniu linku
        window.location.href = 'landing_page.html'; // Przekierowanie na stronę landing_page.html
    });
});