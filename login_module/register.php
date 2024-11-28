<?php
session_start();

if (isset($_POST['email'])) {
    $all_OK = true;

    // Sprawdź poprawność imienia
    $name = $_POST['name'];
    if (strlen($name) < 3 || strlen($name) > 20) {
        $all_OK = false;
        $_SESSION['e_name'] = "Imię musi posiadać od 3 do 20 znaków!";
    }
    if (ctype_alnum($name) == false) {
        $all_OK = false;
        $_SESSION['e_name'] = "Imię może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

    // Sprawdź poprawność nazwiska
    $surname = $_POST['surname'];
    if (strlen($surname) < 3 || strlen($surname) > 20) {
        $all_OK = false;
        $_SESSION['e_surname'] = "Nazwisko musi posiadać od 3 do 20 znaków!";
    }
    if (ctype_alnum($surname) == false) {
        $all_OK = false;
        $_SESSION['e_surname'] = "Nazwisko może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

    // Sprawdź poprawność emaila
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $all_OK = false;
        $_SESSION['e_email'] = "Podaj poprawny adres email!";
    }

    // Sprawdź poprawność hasła
    $password = $_POST['password'];
    $password_repeat = $_POST['password-repeat'];
    if (strlen($password) < 8 || strlen($password) > 20) {
        $all_OK = false;
        $_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków!";
    }
    if ($password != $password_repeat) {
        $all_OK = false;
        $_SESSION['e_password'] = "Podane hasła nie są identyczne!";
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Sprawdź czy zaakceptowano regulamin
    if (!isset($_POST['accept-terms'])) {
        $all_OK = false;
        $_SESSION['e_accept-terms'] = "Zaakceptuj regulamin!";
    }

    // Sprawdź reCAPTCHA
    $secret = "6Lc1KYwqAAAAAA50kwdX1l55ZD9yrRfdsZhSY4g2";

    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

    $response = json_decode($check);
    if($response->success == false) {
        $all_OK = false;
        $_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem!";
    }

    require_once "../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0) {
            $all_OK = false;
            throw new Exception(mysqli_connect_errno());
        }
        else{
            // Czy email już istnieje?
            $result = $connection->query("SELECT User_id FROM users WHERE email='$email'");
            if (!$result) throw new Exception($connection->error);

            $how_many_emails = $result->num_rows;
            if($how_many_emails > 0) {
                $all_OK = false;
                $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu email!";
            }

            if ($all_OK == true) {
                // Wszystkie testy zaliczone, dodajemy użytkownika do bazy
                $current_date = date("Y-m-d H:i:s");

                if ($connection->query("INSERT INTO users VALUES (NULL, '$email', '$password_hash', NULL, NULL, '$name', '$surname', '$$current_date')")) {
                    $_SESSION['successful_registration'] = true;
                    header('Location: ../welcome.php');
                } else {
                    throw new Exception($connection->error);
                }
            }

            $connection->close();
        }
    }
    catch(Exception $e){
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
        echo '<br />Informacja developerska: '.$e;
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
</head>
<body>
<header>
    <h1>Dołącz do BudApp</h1>
</header>
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


            <div class="g-recaptcha" data-sitekey="6Lc1KYwqAAAAAPQ27PJ0r2FldpfgyyLTPkuAFQ-Q"></div>
            <?php
                if (isset($_SESSION['e_bot'])) {
                    echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                    unset($_SESSION['e_bot']);
                }
            ?>

            
            <button type="submit" class="register-btn">Zarejestruj się</button>
        </form>
        <a class="login-link" href="login.php">Masz już konto? Zaloguj się</a>
    </section>
</main>
<footer>
    <p>&copy; 2024 BudApp. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
