document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('passwordConfirmationModal');
    const notification = document.getElementById('passwordUpdateNotification');

    // Ukryj modal i powiadomienie przy ładowaniu strony
    if (modal) modal.style.display = 'none';
    if (notification) notification.classList.add('hidden');

    // Obsługa kliknięcia przycisku zmiany hasła
    document.getElementById('submitPasswordChange').addEventListener('click', function (event) {
        event.preventDefault();

        // Pobierz dane z formularza
        const form = document.forms['passwordForm'];
        const formData = new FormData(form);

        // Wyświetl modal do potwierdzenia
        modal.style.display = 'flex';
    });

    // Potwierdzenie zmiany hasła
    document.getElementById('confirmPasswordChange').addEventListener('click', function () {
        const form = document.forms['passwordForm'];
        const formData = new FormData(form);

        fetch('settings_utils/update_password.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                modal.style.display = 'none';

                if (data.success) {
                    showNotification("Hasło zostało zaktualizowane pomyślnie!", true);
                } else {
                    showNotification(data.message || "Wystąpił błąd podczas aktualizacji hasła.", false);
                }
            })
            .catch(() => {
                modal.style.display = 'none';
                showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
            });
    });

    // Zamknięcie modala, jeśli użytkownik anuluje zmianę hasła
    document.getElementById('cancelPasswordChange').addEventListener('click', function () {
        modal.style.display = 'none';
    });

    function showNotification(message, isSuccess) {
        const notification = document.getElementById('passwordUpdateNotification');
        const notificationMessage = notification.querySelector('#notificationMessage');

        if (!notification || !notificationMessage) {
            console.error('Nie znaleziono elementu powiadomienia w DOM.');
            return;
        }

        // Ustaw treść powiadomienia
        notificationMessage.textContent = message;

        // Dodaj odpowiednią klasę (success/error)
        notification.className = 'notification'; // Reset klas
        notification.classList.add(isSuccess ? 'success' : 'error');
        notification.classList.remove('hidden');

        // Wymuszenie widoczności
        notification.style.display = 'block';

        // Ukryj powiadomienie po 5 sekundach
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.style.display = 'none';
        }, 5000);
    }

    // Obsługa przycisku zamykania powiadomienia
    document.getElementById('closeNotification').addEventListener('click', function () {
        notification.classList.add('hidden');
    });
});
