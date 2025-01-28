<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja/Logowanie</title>
    <link rel="stylesheet" href="register.css">
    <!-- <link rel="stylesheet" href="styles/style.css"> -->
    <script src="toggle-password.js"></script>
    <link rel="icon" type="image/x-icon" href="../pictures/logo.webp">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../pictures/logo.webp" alt="Logo" class="logo">
            <span class="app-name">BudApp</span>
        </div>
    </header>
    <h1>Dołącz do BudApp</h1>
    <main>
        <section class="form-container">
            <h2>Witamy z powrotem</h2>
            <!-- Komunikat błędu -->
            <?php
                if (isset($_SESSION['error'])) {
                    echo "<div class='error-message'>".$_SESSION['error']."</div>";
                    unset($_SESSION['error']);
                }
            ?>
            <form action="login_handler.php" method="post">
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required>
                
                <label for="login-password">Hasło:</label>
                <div class="password-container">
                    <input type="password" id="login-password" name="password" required>
                    <button type="button" id="toggle-password" class="toggle-password">
                        <img src="../icons/visibility_on.png">
                    </button>
                </div>
                
                <button type="submit" class="login-btn">Zaloguj się</button>
            </form>
            <a class="register-link" href="register.php">Nie masz konta? Zarejestruj się</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 BudApp. Wszystkie prawa zastrzeżone.</p>
    </footer>
    <script src="toggle-password.js"></script>
</body>
</html>
