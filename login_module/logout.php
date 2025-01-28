<?php
    session_start();

    // Wyczyść zmienne sesji
    session_unset();

    // Zniszcz sesję
    session_destroy();
?>
<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wylogowano</title>
    <link rel="stylesheet" href="register.css"> <!-- Ścieżka do pliku CSS -->
    <link rel="icon" type="image/x-icon" href="../pictures/logo.webp">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../pictures/logo.webp" alt="Logo" class="logo">
            <span class="app-name">BudApp</span>
        </div>
    </header>
    <!-- <h1>Dołącz do BudApp</h1> -->
    <main>
        <section class="form-container">
            <h2>Wylogowano pomyślnie!</h2>
            <p>Dziękujemy za skorzystanie z BudApp. Zapraszamy ponownie!</p>
            <div class="actions">
                <a href="../landing_page.php">
                    <button type="button">Strona główna</button>
                </a>
                <a href="../login_module/login.php">
                    <button type="button">Zaloguj się ponownie</button>
                </a>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 BudApp. Wszystkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
