<?php
    // spróbuj u kogoś zalogować się bez podania loginu i hasła
    // ' OR User_id=9 -- 
    // SQL INJECTION
    session_start();
    if (!isset($_POST["email"]) || (!isset($_POST["password"]))) {
        // przekierowanie do strony logowania, jeśli ktoś chce wejść na stronę bez logowania
        header('Location: login.php');
        exit();
    }
    require_once "../connect.php";

    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    // '@' - operator ignorowania błędów (wyciszenia)
    if ($polaczenie->connect_errno != 0) {
        echo "Eror:".$polaczenie->connect_errno;
    }
    else{
        $login = $_POST['email'];
        $password = $_POST['password'];  
        
        $login  = htmlentities($login, ENT_QUOTES, "UTF-8");

        $sql = "SELECT * FROM users WHERE email = '$login' AND password = '$password'";
        // sprintf wstawia zmienne do zapytania mysql_real_escape_string zabezpiecza przed SQL Injection
        if ($result = @$polaczenie->query(sprintf("SELECT * FROM users WHERE email = '%s'",
                mysqli_real_escape_string($polaczenie, $login)))) {
            // sprawdzamy czy w zapytaniu wystąpił błąd (mysql nie mógł wykonać zapytania)
            $user_count = $result->num_rows;
            if ($user_count > 0) {
                $row  = $result->fetch_assoc();

                if (password_verify($password, $row['Password'])){
                    $_SESSION['logged'] = true;

                    // utworzenie tablicy assocjacyjenej z wyników zapytania
    
                    $_SESSION['id'] = $row['User_id'];
                    $_SESSION['name'] = $row["Name"];
    
                    unset($_SESSION['error']); // uswamy błąd z sesji gdy udało się zalogować
                    $result->free_result();
                    header('Location: ../index.php');
                }
                else{
                    // dobry login, złe hasło
                    $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                    header('Location: login.php');
                }


            }
            else{
                // zły login, obojętnie jakie hasło
                $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                header('Location: login.php');
            }
        }
        

        $polaczenie->close();
    }

?>