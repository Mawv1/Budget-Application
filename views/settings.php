<?php
    session_start();

    if (!isset($_SESSION['logged'])) {
        header('Location: ../login_module/login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudApp</title>
    <link rel="stylesheet" href="../styles/pages/settings.css">
    <link rel="icon" type="image/x-icon" href="../pictures/logo.webp">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../pictures/logo.webp" alt="Logo" class="logo">
            <span class="app-name">BudApp</span>
        </div>
    </header>
    <div class="content">
        <h1>Ustawienia konta</h1>
        <?php 
            echo "<span>Email: ".$_SESSION['email']."</span>";
        ?>
        <div class="comeback">
            <button onclick="window.location.href='../index.php'">
                <img src="../icons/arrow_back.png" alt="Powrót">
            </button>
        </div>

        <!-- Formularz zmiany adresu e-mail -->
        <form action="settings_utils/update_email.php" method="POST" class="form-section" name="emailForm">
            <h2>Zmień adres e-mail</h2>
            <div class="form-group">
                <label for="email">Adres e-mail:</label>
                <input type="email" id="new_email" name="new_email" 
                    value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email'], ENT_QUOTES) : ''; ?>" 
                    required>
                <label for="repeat-email">Powtórz adres e-mail:</label>
                <input type="email" id="repeat_email" name="repeat_email" required>
            </div>
            <button type="button" class="btn" id="submitEmailChange">Zmień e-mail</button>
        </form>

        <!-- Formularz zmiany hasła -->
        <form action="settings_utils/update_password.php" method="POST" class="form-section">
            <h2>Zmień hasło</h2>
            <div class="form-group">
                <label for="current-password">Obecne hasło:</label>
                <input type="password" id="current-password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new-password">Nowe hasło:</label>
                <input type="password" id="new-password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Potwierdź nowe hasło:</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Zmień hasło</button>
        </form>
    </div>

    <!-- Modal dla potwierdzenia zmiany e-mail -->
    <div id="emailConfirmationModal" class="modal">
        <div class="modal-content">
            <h3>Potwierdź zmianę e-mail</h3>
            <p>Czy na pewno chcesz zmienić e-mail na <span id="newEmailDisplay"></span>?</p>
            <button id="confirmEmailChange">Tak</button>
            <button id="cancelEmailChange">Anuluj</button>
        </div>
    </div>

    <!-- Powiadomienie o zmianie e-maila -->
    <div id="emailUpdateNotification" class="notification hidden">
        <span id="notificationMessage"></span>
        <button id="closeNotification">X</button>
    </div>


<script>
    // Funkcja wywoływana po kliknięciu przycisku zmiany e-maila
    document.getElementById('submitEmailChange').addEventListener('click', function(event) {
        event.preventDefault();
        console.log('Kliknięto przycisk Zmień e-mail');
        const newEmail = document.getElementById('new_email').value;
        console.log('Nowy e-mail:', newEmail);
        document.getElementById('newEmailDisplay').textContent = newEmail;
        document.getElementById('emailConfirmationModal').style.display = 'flex';
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
            // Ukryj modal po odpowiedzi
            document.getElementById('emailConfirmationModal').style.display = 'none';

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
            document.getElementById('emailConfirmationModal').style.display = 'none';
            showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
        });
    });


    // Zamknięcie modala, jeśli użytkownik anuluje zmianę e-maila
    document.getElementById('cancelEmailChange').addEventListener('click', function() {
        document.getElementById('emailConfirmationModal').style.display = 'none'; // Ukryj modal
    });

    // Obsługa formularza zmiany e-maila
    document.forms['emailForm'].addEventListener('submit', function(event) {
        event.preventDefault(); // Zatrzymaj domyślną akcję formularza

        const formData = new FormData(this);
        fetch('settings_utils/update_email.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Odpowiedź serwera:', data); // Dodaj logowanie odpowiedzi
            // Reszta kodu
        })
        .catch(error => {
            console.error('Błąd połączenia z serwerem:', error); // Logowanie błędów
            showNotification("Nie udało się połączyć z serwerem. Spróbuj ponownie później.", false);
        });
    });

    // Funkcja wyświetlania powiadomień
    function showNotification(message, isSuccess) {
        const notification = document.getElementById('emailUpdateNotification');
        const notificationMessage = notification.querySelector('#notificationMessage');
        
        // Ustaw treść powiadomienia
        notificationMessage.textContent = message;

        // Dodaj odpowiednią klasę (success/error)
        notification.className = 'notification'; // Reset klas
        notification.classList.add(isSuccess ? 'success' : 'error');
        notification.classList.remove('hidden');

        // Wymuszenie widoczności
        notification.style.display = 'block';
        console.log('Powiadomienie wymuszone na widoczne:', notification);

        // Ukryj powiadomienie po 5 sekundach
        setTimeout(() => {
            notification.classList.add('hidden');
            notification.style.display = 'none';
        }, 5000);
    }


    // Obsługa przycisku zamykania powiadomienia
    document.getElementById('closeNotification').addEventListener('click', function() {
        document.getElementById('emailUpdateNotification').classList.add('hidden'); // Ukryj powiadomienie
    });

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('emailConfirmationModal');
        const notification = document.getElementById('emailUpdateNotification');
        
        // Ukryj modal i powiadomienie przy ładowaniu strony
        if (modal) modal.style.display = 'none';
        if (notification) notification.classList.add('hidden');
    });

</script>

</body>
</html>
