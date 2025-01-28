document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('removeBudgetModal');
    const notification = document.getElementById('budgetRemoveNotification');
    const removeButtons = document.querySelectorAll('.delete-budget-btn'); // Wszystkie przyciski usuwania
    let selectedForm = null; // Formularz powiązany z klikniętym przyciskiem

    // Ukryj modal i powiadomienie na starcie
    if (modal) modal.style.display = 'none';
    if (notification) notification.classList.add('hidden');

    // Obsługa kliknięcia przycisku "Usuń budżet"
    removeButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Zatrzymanie domyślnego działania
            selectedForm = this.closest('form'); // Przechowaj formularz
            modal.style.display = 'flex'; // Wyświetl modal
        });
    });

    // Obsługa potwierdzenia usunięcia budżetu
    document.getElementById('confirmBudgetRemove').addEventListener('click', function (event) {
        if (!selectedForm) return;
        event.preventDefault(); // Zatrzymanie domyślnego działania formularza
        modal.style.display = 'none'; // Ukryj modal

        const formData = new FormData(selectedForm);

        fetch('budget_utils/remove_budget.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, true);
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(() => {
                showNotification("Błąd połączenia z serwerem.", false);
            });
    });

    // Anulowanie usunięcia budżetu
    document.getElementById('cancelBudgetRemove').addEventListener('click', function () {
        modal.style.display = 'none'; // Ukryj modal
    });

    // Funkcja wyświetlania powiadomień
    function showNotification(message, isSuccess) {
        if (!notification) {
            console.error('Nie znaleziono elementu powiadomienia w DOM.');
            return;
        }

        const notificationMessage = document.getElementById('budgetRemoveNotification');
        if (!notificationMessage) {
            console.error('Nie znaleziono elementu wiadomości w powiadomieniu.');
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
            location.reload(); // Przeładuj stronę, aby zaktualizować dane
        }, 2000); // 2 sekund, aby dać użytkownikowi czas na przeczytanie
    }

    // Obsługa zamykania powiadomienia
    document.getElementById('closeNotification').addEventListener('click', function () {
        notification.classList.add('hidden');
    });
});
