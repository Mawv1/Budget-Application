document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('passwordConfirmationModal');
    const notification = document.getElementById('passwordUpdateNotification');

    // Ukryj modal i powiadomienie przy ładowaniu strony
    if (modal) modal.style.display = 'none';
    if (notification) notification.classList.add('hidden');

    document.getElementById('submitPasswordChange').addEventListener('click', function(event) {
        event.preventDefault();
        modal.style.display = 'flex';
    });

    document.getElementById('confirmPasswordChange').addEventListener('click', function() {
        const form = document.forms['passwordForm'];
        const formData = new FormData(form);

        fetch('update_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            modal.style.display = 'none';

            if (data.success) {
                showNotification(data.message, true);
            } else {
                showNotification(data.message || "Wystąpił błąd podczas zmiany hasła.", false);
            }
        })
        .catch(() => {
            modal.style.display = 'none';
            showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
        });
    });

    document.getElementById('cancelPasswordChange').addEventListener('click', function() {
        modal.style.display = 'none';
    });

    function showNotification(message, isSuccess) {
        const notificationMessage = notification ? notification.querySelector('#notificationMessage') : null;
    
        if (!notificationMessage) {
            console.error("Element 'notificationMessage' nie został znaleziony.");
            return;
        }
    
        notificationMessage.textContent = message;
    
        notification.className = 'notification';
        notification.classList.add(isSuccess ? 'success' : 'error');
        notification.classList.remove('hidden');
    
        notification.style.display = 'block';
    
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.style.display = 'none';
        }, 5000);
    }    

    document.getElementById('closeNotification').addEventListener('click', function() {
        notification.classList.add('hidden');
    });
});
