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

    try{
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if($connection->connect_errno != 0){
            throw new Exception(mysqli_connect_errno());
        }
        else{
            $login = $_POST['email'];
            $password = $_POST['password'];  
            
            $login  = htmlentities($login, ENT_QUOTES, "UTF-8");
            // prepare statement, bind
            $sql = "SELECT * FROM users WHERE email = '$login' AND password = '$password'";
            // sprintf wstawia zmienne do zapytania mysql_real_escape_string zabezpiecza przed SQL Injection
            $result = $connection->query(sprintf("SELECT * FROM users WHERE email = '%s'",
            mysqli_real_escape_string($connection, $login)));
            if (!$result) throw new Exception($connection->error); 

            // sprawdzamy czy w zapytaniu wystąpił błąd (mysql nie mógł wykonać zapytania)
            $user_count = $result->num_rows;
            if ($user_count > 0) {
                $row  = $result->fetch_assoc();

                if (password_verify($password, $row['Password'])){
                    $_SESSION['logged'] = true;

                    // utworzenie tablicy assocjacyjenej z wyników zapytania
    
                    $_SESSION['id'] = $row['User_id'];
                    $_SESSION['name'] = $row["Name"];
                    $_SESSION['email'] = $row['Email'];
    
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

    }
    catch(Exception $e){
        echo '<span style="color:red">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
        // echo '<br />Informacja developerska: '.$e;
    }
?>