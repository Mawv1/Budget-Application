document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteTransactionModal');
    const notification = document.getElementById('transactionDeleteNotification');
    const deleteButtons = document.querySelectorAll('.delete-transaction-btn'); // Wszystkie przyciski usuwania
    let selectedForm = null; // Formularz powiązany z klikniętym przyciskiem

    // Ukryj modal i powiadomienie na starcie
    if (modal) modal.style.display = 'none';
    if (notification) notification.classList.add('hidden');

    // Obsługa kliknięcia przycisku "Usuń transakcję"
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Zatrzymanie domyślnego działania
            selectedForm = this.closest('form'); // Przechowaj formularz
            modal.style.display = 'flex'; // Wyświetl modal
        });
    });

    // Obsługa potwierdzenia usunięcia transakcji
    document.getElementById('confirmTransactionDelete').addEventListener('click', function () {
        if (!selectedForm) return;

        const formData = new FormData(selectedForm);

        fetch('delete_transaction.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                modal.style.display = 'none';

                if (data.success) {
                    showNotification("Transakcja usunięta pomyślnie!", true);
                    selectedForm.closest('tr').remove(); // Usuń transakcję z tabeli
                } else {
                    showNotification(data.message || "Wystąpił błąd podczas usuwania transakcji.", false);
                }
            })
            .catch(() => {
                modal.style.display = 'none';
                showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
            });
    });

    // Anulowanie usunięcia transakcji
    document.getElementById('cancelTransactionDelete').addEventListener('click', function () {
        modal.style.display = 'none'; // Ukryj modal
    });

    // Funkcja wyświetlania powiadomień
    function showNotification(message, isSuccess) {
        const notificationMessage = document.getElementById('notificationMessage');

        if (!notification || !notificationMessage) {
            console.error('Nie znaleziono elementu powiadomienia w DOM.');
            return;
        }

        notificationMessage.textContent = message;

        notification.className = 'notification'; // Reset klas
        notification.classList.add(isSuccess ? 'success' : 'error');
        notification.classList.remove('hidden');

        notification.style.display = 'block';

        // Ukryj powiadomienie po 5 sekundach
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.style.display = 'none';
        }, 5000);
    }

    // Obsługa zamykania powiadomienia
    document.getElementById('closeNotification').addEventListener('click', function () {
        notification.classList.add('hidden');
    });
});
