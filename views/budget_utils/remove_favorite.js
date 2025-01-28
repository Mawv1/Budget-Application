document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('removeFavoriteModal');
    const notification = document.getElementById('favoriteRemoveNotification');
    const removeFavoriteButtons = document.querySelectorAll('.remove-favorite-btn'); // Przyciski usuwania z ulubionych
    let selectedForm = null; // Formularz powiązany z klikniętym przyciskiem

    // Ukryj modal i powiadomienie na starcie
    if (modal) modal.style.display = 'none';
    if (notification) notification.classList.add('hidden');

    // Obsługa kliknięcia przycisku "Usuń z ulubionych"
    removeFavoriteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Zatrzymanie domyślnego działania
            selectedForm = this.closest('form'); // Przechowaj formularz
            modal.style.display = 'flex'; // Wyświetl modal
        });
    });

    // Obsługa potwierdzenia usunięcia z ulubionych
    document.getElementById('confirmFavoriteRemove').addEventListener('click', function () {
        if (!selectedForm) return;
        event.preventDefault();
        modal.style.display = 'none'; // Ukryj modal

        const formData = new FormData(selectedForm);

        fetch('budget_utils/remove_favorite.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                modal.style.display = 'none';

                if (data.success) {
                    showNotification(data.message, true);
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(() => {
                showNotification("Błąd połączenia z serwerem.", false);
            });

        // fetch('budget_utils/remove_favorite.php', {
        //     method: 'POST',
        //     body: formData
        // })
        //     .then(response => response.json())
        //     .then(data => {
        //         console.log('Odpowiedź serwera:', data);
        //         modal.style.display = 'none';
        
        //         if (data.success) {
        //             console.log('Usunięto pomyślnie. Wyświetlanie powiadomienia.');
        //             showNotification("Usunięto z ulubionych pomyślnie!", true);
        
        //             // Ukryj przycisk usuwania
        //             const removeButton = selectedForm.closest('li').querySelector('.remove-favorite-btn');
        //             if (removeButton) removeButton.style.display = 'none';
        
        //             const favoriteButton = selectedForm.closest('li').querySelector('.favorite-btn');
        //             if (favoriteButton) favoriteButton.style.display = 'inline';
        
        //             // Odśwież stronę po 2 sekundach
        //             setTimeout(() => {
        //                 location.reload();
        //             }, 2000);
        //         } else {
        //             console.error('Błąd usuwania:', data.message);
        //             showNotification(data.message || "Wystąpił błąd podczas usuwania z ulubionych.", false);
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Błąd połączenia z serwerem:', error);
        //         modal.style.display = 'none';
        //         showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
        //     });
        
    });

    // Anulowanie usunięcia z ulubionych
    document.getElementById('cancelFavoriteRemove').addEventListener('click', function () {
        modal.style.display = 'none'; // Ukryj modal
    });

    // Funkcja wyświetlania powiadomień
    function showNotification(message, isSuccess) {
        const notificationMessage = document.getElementById('favoriteRemoveNotification');

        if (!notification || !notificationMessage) {
            console.error('Nie znaleziono elementu powiadomienia w DOM.');
            return;
        }

        notificationMessage.textContent = message;

        notification.className = 'notification'; // Reset klas
        notification.classList.add(isSuccess ? 'success' : 'error');
        notification.classList.remove('hidden');

        notification.style.display = 'block';

        // Ukryj powiadomienie po 2 sekundach
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.style.display = 'none';
            location.reload();
        }, 2000);
    }

    // Obsługa zamykania powiadomienia
    document.getElementById('closeNotification').addEventListener('click', function () {
        notification.classList.add('hidden');
    });
});
