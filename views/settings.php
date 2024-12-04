<?php
    session_start();

    if (!isset($_SESSION['logged'])) {
        header('Location: login_module/login.php');
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

        <!-- Formularz zmiany adresu e-mail -->
        <form action="update_email.php" method="POST" class="form-section">
            <h2>Zmień adres e-mail</h2>
            <div class="form-group">
                <label for="email">Adres e-mail:</label>
                <input type="email" id="email" name="email" 
                    value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email'], ENT_QUOTES) : ''; ?>" 
                    required>
            </div>
            <!-- <?php
                    echo "<span>Witaj ".$_SESSION['name']."!</span>";
            ?> -->
            <button type="submit" class="btn">Zmień e-mail</button>
        </form>

        <!-- Formularz zmiany hasła -->
        <form action="update_password.php" method="POST" class="form-section">
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
</body>
</html>
