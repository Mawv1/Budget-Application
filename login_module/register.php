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
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
    <header>
        <h1>Dołącz do BudApp</h1>
    </header>
    <main>
        <section class="form-container">
            <h2>Zarejestruj się</h2>
            <form method="post">
                <label for="register-name">Imię:</label>
                <input type="text" id="register-name" name="name" required>
                <label for="register-surname">Nazwisko:</label>
                <input type="text" id="register-surname" name="surname" required>
                <label for="register-email">Email:</label>
                <input type="email" id="register-email" name="email" required>
                
                <label for="register-password">Hasło:</label>
                <div class="password-container">
                    <input type="password" id="register-password" name="password" required>
                    <button type="button" id="toggle-password-main" class="toggle-password">
                        <img src="../icons/visibility_on.png" alt="Pokaż hasło">
                    </button>
                </div>

                <label for="register-password-repeat">Powtórz hasło:</label>
                <div class="password-container">
                    <input type="password" id="register-password-repeat" name="password-repeat" required>
                    <button type="button" id="toggle-password-repeat" class="toggle-password">
                        <img src="../icons/visibility_on.png" alt="Pokaż hasło">
                    </button>
                </div>

                <div class="g-recaptcha" data-sitekey="6Lc1KYwqAAAAAPQ27PJ0r2FldpfgyyLTPkuAFQ-Q"></div>
                <button type="submit" class="register-btn">Zarejestruj się</button>
            </form>
            <a class = "login-link" href = "login.php">Masz już konto? Zaloguj się</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 BudApp. Wszystkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>


<!-- Najlepiej formularz logowania przerzucać na php -->
<!-- A rejestracja - validowanie emaila odrzucać frontendowo (chroni to przed ddos) -->