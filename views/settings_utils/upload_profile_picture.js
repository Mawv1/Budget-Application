document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('confirmProfilePictureUpdateModal');
    const notification = document.getElementById('profilePictureUploadNotification');
    const profilePreview = document.getElementById('profilePreview');
    const fileInput = document.querySelector('input[name="file"]'); // Input do wyboru pliku

    if (modal) modal.style.display = 'none';
    if (notification) notification.classList.add('hidden');

    document.getElementById('submitProfilePicture').addEventListener('click', function (event) {
        event.preventDefault();
        modal.style.display = 'flex';
    });

    document.getElementById('confirmProfilePictureUpload').addEventListener('click', function () {
        const form = document.forms['profilePictureForm'];
        const formData = new FormData(form);

        fetch('settings_utils/upload_profile_picture.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                modal.style.display = 'none';
                showNotification(data.message, data.success);

                if (data.success) {
                    // Zaktualizuj podgląd zdjęcia
                    updateProfilePreview(data.fileName);
                }
            })
            .catch(() => {
                modal.style.display = 'none';
                showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
            });
    });

    document.getElementById('cancelProfilePictureUpload').addEventListener('click', function () {
        modal.style.display = 'none';
    });

    fileInput.addEventListener('change', function () {
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (profilePreview) {
                    profilePreview.src = e.target.result; // Wyświetl podgląd lokalny
                }
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    });

    function showNotification(message, isSuccess) {
        const notification = document.getElementById('profilePictureUploadNotification');
        const notificationMessage = notification.querySelector('#notificationMessage');

        if (!notification || !notificationMessage) {
            console.error('Nie znaleziono elementu powiadomienia w DOM.');
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
            location.reload();
        }, 2000);
    }

    function updateProfilePreview(fileName) {
        if (profilePreview) {
            // Aktualizacja obrazka z unikalnym identyfikatorem, aby uniknąć cache'owania
            profilePreview.src = `../pictures/uploads/${fileName}?t=${new Date().getTime()}`;
        }
    }
});
