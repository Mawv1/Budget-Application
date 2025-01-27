document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('emailConfirmationModal');
    const notification = document.getElementById('emailUpdateNotification'); // Poprawiona nazwa ID

    // Ukryj modal i powiadomienie przy ładowaniu strony
    if (modal) modal.style.display = 'none';
    if (notification) notification.classList.add('hidden');

    // Obsługa kliknięcia przycisku zmiany e-maila
    document.getElementById('submitEmailChange').addEventListener('click', function(event) {
        event.preventDefault();
        const newEmail = document.getElementById('new_email').value;
        
        document.getElementById('newEmailDisplay').textContent = newEmail;
        modal.style.display = 'flex';
    });

    // Potwierdzenie zmiany e-maila
    document.getElementById('confirmEmailChange').addEventListener('click', function () {
        const form = document.forms['emailForm'];
        const formData = new FormData(form);

        fetch('settings_utils/update_email.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            modal.style.display = 'none';
        
            if (data.success) {
                showNotification("E-mail został zaktualizowany pomyślnie!", true);
        
                // Zaktualizuj wyświetlany e-mail w interfejsie użytkownika
                if (data.updated_email) {
                    document.querySelector(".content span").textContent = `Email: ${data.updated_email}`;
                }
            } else {
                showNotification(data.message || "Wystąpił błąd podczas aktualizacji e-maila.", false);
            }
        })        
        .catch(() => {
            modal.style.display = 'none';
            showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
        });
    });

    // Zamknięcie modala, jeśli użytkownik anuluje zmianę e-maila
    document.getElementById('cancelEmailChange').addEventListener('click', function() {
        modal.style.display = 'none';
    });

    function showNotification(message, isSuccess) {
        const notification = document.getElementById('emailUpdateNotification'); // Poprawny ID
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
    
        // Ukryj powiadomienie po 2 sekundach
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.style.display = 'none';
            location.reload();
        }, 2000);
    }    

    // Obsługa przycisku zamykania powiadomienia
    document.getElementById('closeNotification').addEventListener('click', function() {
        notification.classList.add('hidden');
    });
});
