<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja/Logowanie</title>
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <h1>Dołącz do aplikacji budżetowej</h1>
    </header>
    <main>
        <section class="form-container">
            <h2>Zarejestruj się</h2>
            <form action="register_handler.php" method="post">
                <label for="register-email">Email:</label>
                <input type="email" id="register-email" name="email" required>
                
                <label for="register-password">Hasło:</label>
                <input type="password" id="register-password" name="password" required>
                
                <button type="submit" class="register-btn">Zarejestruj się</button>
            </form>

            <a class = "login-link" href = "login.php">Masz już konto? Zaloguj się</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Budget Application. Wszystkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>


<!-- Najlepiej formularz logowania przerzucać na php -->
<!-- A rejestracja - validowanie emaila odrzucać frontendowo (chroni to przed ddos) -->