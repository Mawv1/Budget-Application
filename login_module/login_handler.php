<?php

    session_start();

    require_once "../connect.php";

    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    // '@' - operator ignorowania błędów (wyciszenia)
    if ($polaczenie->connect_errno != 0) {
        echo "Eror:".$polaczenie->connect_errno;
    }
    else{
        $login = $_POST['email'];
        $password = $_POST['password'];  
        
        $sql = "SELECT * FROM users WHERE email = '$login' AND password = '$password'";
        
        if ($result = @$polaczenie->query($sql)){
            // sprawdzamy czy w zapytaniu wystąpił błąd (mysql nie mógł wykonać zapytania)
            $user_count = $result->num_rows;
            if ($user_count > 0) {
                $_SESSION['logged'] = true;

                $row  = $result->fetch_assoc();
                // utworzenie tablicy assocjacyjenej z wyników zapytania

                $_SESSION['id'] = $row['User_id'];
                $_SESSION['name'] = $row["Name"];

                unset($_SESSION['error']); // uswamy błąd z sesji gdy udało się zalogować
                $result->free_result();
                header('Location: ../index.php');
            }
            else{
                // nie ma w bazie nikogo o tym loginie i haśle
                $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                header('Location: login.php');
            }
        }
        

        $polaczenie->close();
    }

?>