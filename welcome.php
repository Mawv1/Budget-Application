<?php
    session_start();

    if (!isset($_SESSION['successful_registration'])) {
        header('Location: index.php');
        exit();
    } else {
        unset($_SESSION['successful_registration']);
    }
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potwierdzenie rejestracji</title>
    <link rel="stylesheet" href="login_module/register.css">
</head>
<body>
<header>
    <h1>BudApp</h1>
</header>
<main>
    <section class="form-container">
        <h2>Rejestracja zakończona!</h2>
        <p style="text-align: center;">Twoje konto zostało utworzone pomyślnie!</p>
        <a href="login.php">
            <button type="button">Zaloguj się</button>
        </a>
    </section>
</main>
<footer>
    <p>&copy; 2024 BudApp. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
