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
            <h2>Witamy z powrotem</h2>
            <form action="login_handler.php" method="post">
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required>
                
                <label for="login-password">Hasło:</label>
                <input type="password" id="login-password" name="password" required>
                
                <button type="submit" class="login-btn">Zaloguj się</button>
            </form>
            <a class = "register-link" href = "register.php">Nie masz konta? Zarejestruj się</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Budget Application. Wszystkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>


<!-- Najlepiej formularz logowania przerzucać na php -->
<!-- A rejestracja - validowanie emaila odrzucać frontendowo (chroni to przed ddos) -->