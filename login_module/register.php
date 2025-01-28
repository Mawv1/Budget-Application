<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $all_OK = true;

    // Pobierz dane z formularza
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_repeat = $_POST['password-repeat'] ?? '';

    // Walidacja danych
    if (strlen($name) < 3 || strlen($name) > 50) {
        $all_OK = false;
        $_SESSION['e_name'] = "Imię musi mieć od 3 do 50 znaków.";
    }
    if (!ctype_alnum($name)) {
        $all_OK = false;
        $_SESSION['e_name'] = "Imię może składać się tylko z liter i cyfr (bez polskich znaków).";
    }

    if (strlen($surname) < 3 || strlen($surname) > 50) {
        $all_OK = false;
        $_SESSION['e_surname'] = "Nazwisko musi mieć od 3 do 50 znaków.";
    }
    if (!ctype_alnum($surname)) {
        $all_OK = false;
        $_SESSION['e_surname'] = "Nazwisko może składać się tylko z liter i cyfr (bez polskich znaków).";
    }

    $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) || $emailSanitized !== $email) {
        $all_OK = false;
        $_SESSION['e_email'] = "Podaj poprawny adres email.";
    }

    if (strlen($password) < 8 || strlen($password) > 20) {
        $all_OK = false;
        $_SESSION['e_password'] = "Hasło musi mieć od 8 do 20 znaków.";
    }
    if ($password !== $password_repeat) {
        $all_OK = false;
        $_SESSION['e_password'] = "Podane hasła nie są identyczne.";
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if (!isset($_POST['accept-terms'])) {
        $all_OK = false;
        $_SESSION['e_accept-terms'] = "Zaakceptuj regulamin.";
    }

    require_once "../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0) {
            throw new Exception($connection->connect_error);
        }

        // Sprawdzenie, czy email już istnieje
        $stmt = $connection->prepare("SELECT User_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $all_OK = false;
            $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu email.";
        }
        $stmt->close();

        if ($all_OK) {
            // Dodanie użytkownika do bazy
            $current_date = date("Y-m-d H:i:s");
            $stmt = $connection->prepare("
                INSERT INTO users (Email, Password, Name, Surname, Register_date, is_admin) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            if (!$stmt) {
                throw new Exception("Błąd w zapytaniu INSERT INTO users: " . $connection->error);
            }

            $is_admin = 0; // Domyślnie użytkownik nie jest administratorem
            $stmt->bind_param("sssssi", $email, $password_hash, $name, $surname, $current_date, $is_admin);

            if ($stmt->execute()) {
                $_SESSION['successful_registration'] = true;

                // Automatyczne logowanie po rejestracji
                $_SESSION['logged'] = true;
                $_SESSION['id'] = $connection->insert_id;
                $_SESSION['name'] = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
                $_SESSION['email'] = htmlspecialchars($email, ENT_QUOTES, "UTF-8");

                header('Location: ../welcome.php');
                exit();
            } else {
                throw new Exception($connection->error);
            }
        }

        $connection->close();
    } catch (Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie.</span>';
        error_log($e->getMessage());
    }
}
?>



<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja/Logowanie</title>
    <link rel="stylesheet" href="register.css">
    <script src="toggle-password.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
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
        <h2>Zarejestruj się</h2>
        <form method="post" action="register.php">

            <label for="register-name">Imię:</label>
            <input type="text" id="register-name" name="name" required>
            <?php
                if (isset($_SESSION['e_name'])) {
                    echo '<div class="error">'.$_SESSION['e_name'].'</div>';
                    unset($_SESSION['e_name']);
                }
            ?>


            <label for="register-surname">Nazwisko:</label>
            <input type="text" id="register-surname" name="surname" required>
            <?php
                if (isset($_SESSION['e_surname'])) {
                    echo '<div class="error">'.$_SESSION['e_surname'].'</div>';
                    unset($_SESSION['e_surname']);
                }
            ?>


            <label for="register-email">Email:</label>
            <input type="email" id="register-email" name="email" required>
            <?php
                if (isset($_SESSION['e_email'])) {
                    echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                    unset($_SESSION['e_email']);
                }
            ?>


            <label for="register-password">Hasło:</label>
            <div class="password-container">
                <input type="password" id="register-password" name="password" required>
                <button type="button" id="toggle-password-main" class="toggle-password">
                    <img src="../icons/visibility_on.png" alt="Pokaż hasło">
                </button>
            </div>
            <?php   
                if (isset($_SESSION['e_password'])) {
                    echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                    unset($_SESSION['e_password']);
                }
            ?>


            <label for="register-password-repeat">Powtórz hasło:</label>
            <div class="password-container">
                <input type="password" id="register-password-repeat" name="password-repeat" required>
                <button type="button" id="toggle-password-repeat" class="toggle-password">
                    <img src="../icons/visibility_on.png" alt="Pokaż hasło">
                </button>
            </div>

            
            <div class="checkbox-container">
                <input type="checkbox" id="accept-terms" name="accept-terms" required>
                <label for="accept-terms">Akceptuję regulamin</label>
            </div>
            <?php
                if (isset($_SESSION['e_accept-terms'])) {
                    echo '<div class="error">'.$_SESSION['e_accept-terms'].'</div>';
                    unset($_SESSION['e_accept-terms']);
                }
            ?>


            <!-- <div class="g-recaptcha" data-sitekey="6LeeU8UqAAAAAG5HXrpzIYOFfZhKNDQbevlY4nTq-Q"></div>
            <?php
                if (isset($_SESSION['e_bot'])) {
                    echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                    unset($_SESSION['e_bot']);
                }
            ?> -->

            
            <button type="submit" class="register-btn">Zarejestruj się</button>
        </form>
        <a class="login-link" href="login.php">Masz już konto? Zaloguj się</a>
    </section>
</main>
<footer>
    <p>&copy; 2025 BudApp. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
