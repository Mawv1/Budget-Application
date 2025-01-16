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
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="icon" type="image/x-icon" href="../pictures/logo.webp">
</head>
<body>
    <header class="header">
        <div class="comeback">
            <div class="logo-container">
                <button onclick="window.location.href='../index.php'">
                    <img src="../pictures/logo.webp" alt="Logo" class="logo">
                    <span class="app-name">BudApp</span>
                </button>
            </div>
        </div>
        <!-- <div class="comeback">
            <button onclick="window.location.href='../index.php'">
                <img src="../icons/arrow_back.png" alt="Powrót">
            </button>
        </div> -->
    </header>
    <div class="content">
        <h1>Ustawienia konta</h1>
        <!-- <?php 
            echo "<span>Email: ".$_SESSION['email']."</span>";
        ?> -->


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
        <form action="settings_utils/update_password.php" method="POST" class="form-section" name ="passwordForm">
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
                <label for="repeat-password">Potwierdź nowe hasło:</label>
                <input type="password" id="repeat-password" name="repeat_password" required>
            </div>
                <button type="button" class="btn" id="submitPasswordChange">Zmień hasło</button>
        </form>

        <!-- Formularz zmiany zdjęcia profilowego -->
        <form action = "settings_utils/upload_profile_picture.php" method="POST" enctype="multipart/form-data" class = "form-section" name="profilePictureForm">
            <h2>Zmień swoje zdjęcie profilowe</h2>
            <div class="form-group">
                <input type="file" name="file">
            </div>
            <div>
                <button type="button" class="btn" id="submitProfilePicture">Zmień profilowe</button>
            </div>
        </form>

        <div class="form-section">
            <h2>Podgląd zdjęcia profilowego</h2>
            <img id="profilePreview" 
                src="../pictures/uploads/<?php echo isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture'], ENT_QUOTES) : 'user-photo.jpg'; ?>" 
                alt="Zdjęcie profilowe">
        </div>
        
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

    <!-- Modal dla potwierdzenia zmiany hasła -->
    <div id="passwordConfirmationModal" class="modal">
        <div class="modal-content">
            <h3>Potwierdź zmianę hasła</h3>
            <p>Czy na pewno chcesz zmienić hasło?</p>
            <button id="confirmPasswordChange">Tak</button>
            <button id="cancelPasswordChange">Anuluj</button>
        </div>
    </div>
    

    <!-- Modal potwierdzenia zmiany zdjęcia profilowego-->
    <div id="confirmProfilePictureUpdateModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3>Potwierdź zmianę zdjęcia profilowego</h3>
            <p>Czy na pewno chcesz zaktualizować swoje zdjęcie profilowe?</p>
            <div class="modal-actions">
                <button id="confirmProfilePictureUpload">Tak, zaktualizuj</button>
                <button id="cancelProfilePictureUpload">Anuluj</button>
            </div>
        </div>
    </div>

    <div id="emailUpdateNotification" class="notification hidden">
        <span id="notificationMessage"></span>
        <button id="closeNotification">X</button>
    </div>

    <!-- Powiadomienie o zmianie e-maila -->
    <div id="passwordUpdateNotification" class="notification hidden">
        <span id="notificationMessage"></span>
        <button id="closeNotification">X</button>
    </div>

    <div id="profilePictureUploadNotification" class="notification hidden">
        <span id="notificationMessage"></span>
        <button id="closeNotification">X</button>
    </div>

    <?php
        $current_year = date("Y");
        echo '<footer class="footer">';
            echo '<p>&copy; '.$current_year.' BudApp. Wszelkie prawa zastrzeżone.</p>';
            echo '<ul class="footer-links">';
                echo '<li><a href="#">Polityka prywatności</a></li>';
                echo '<li><a href="#">Regulamin</a></li>';
                echo '<li><a href="#">Kontakt</a></li>';
            echo '</ul>';
        echo '</footer>';
    ?>

    <script src="settings_utils/update_email.js"></script>
    <script src="settings_utils/update_password.js"></script>
    <script src="settings_utils/upload_profile_picture.js"></script>
</body>
</html>
