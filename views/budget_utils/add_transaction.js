document.addEventListener('DOMContentLoaded', function () {
    const notification = document.getElementById('transactionNotification');
    const notificationMessage = document.getElementById('transactionNotificationMessage');
    const form = document.querySelector('.add-expense');
    const backButton = document.getElementById("backButton");

    if (backButton) {
        backButton.addEventListener("click", () => {
            window.history.back(); // Cofnij użytkownika do poprzedniej strony
        });
    }

    // Funkcja wyświetlania powiadomień
    function showTransactionNotification(message, isSuccess) {
        if (!notification || !notificationMessage) {
            console.error('Nie znaleziono elementu powiadomienia w DOM.');
            return;
        }

        // Ustaw wiadomość powiadomienia
        notificationMessage.textContent = message;

        // Resetuj klasy i dodaj odpowiednią klasę (success/error)
        notification.className = 'notification';
        notification.classList.add(isSuccess ? 'success' : 'error');
        notification.classList.remove('hidden');

        // Wyświetl powiadomienie
        notification.style.display = 'block';

        // Ukryj powiadomienie po 5 sekundach
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.style.display = 'none';
        }, 5000);
    }

    // Obsługa zamykania powiadomienia
    document.getElementById('closeTransactionNotification').addEventListener('click', function () {
        if (notification) {
            notification.classList.add('hidden');
            notification.style.display = 'none';
        }
    });

    // Obsługa przesyłania formularza
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Zatrzymaj domyślne przesyłanie formularza

        const formData = new FormData(form); // Pobierz dane formularza
        const actionUrl = form.getAttribute('action'); // Pobierz adres z atrybutu action

        // Wykonaj zapytanie do serwera
fetch(actionUrl, {
    method: 'POST',
    body: formData
})
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Wyświetl powiadomienie o sukcesie
            showTransactionNotification(data.message, true);

            // Odśwież stronę po krótkim czasie (np. 1 sekunda)
            setTimeout(() => {
                location.reload();
            }, 1000); // Odśwież po 1 sekundzie
        } else {
            // Wyświetl powiadomienie o błędzie
            showTransactionNotification(data.message, false);
        }
    })
    .catch(() => {
        // Wyświetl powiadomienie o błędzie połączenia
        showTransactionNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
    });

    });
});
