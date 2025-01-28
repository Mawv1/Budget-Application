<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}
header('Content-Type: application/json');

function updatePassword($currentPassword, $newPassword) {
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "demo";

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        error_log("Database connection error: " . $conn->connect_error);
        echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
        exit();
    }

    $userId = $_SESSION['id'];

    // Pobierz aktualne hasło użytkownika z bazy danych
    $sql = "SELECT Password FROM users WHERE User_id = '$userId'";
    $result = $conn->query($sql);

    // if($row = $result->fetch_assoc() == false){
    //     echo json_encode(["success" => false, "message" => "result->fetch_assoc jest false"]);
    //     exit();
    // }

    if ($result && $row = $result->fetch_assoc()) {
        if (!password_verify($currentPassword, $row['Password'])) {
            echo json_encode(["success" => false, "message" => "Bieżące hasło jest nieprawidłowe."]);
            $conn->close();
            exit();
        }

        // Aktualizuj hasło w bazie danych
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET Password = '$hashedPassword' WHERE User_id = '$userId'";

        if ($conn->query($updateSql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Hasło zostało zaktualizowane pomyślnie!"]);
        } else {
            error_log("SQL error: " . $conn->error);
            echo json_encode(["success" => false, "message" => "Błąd podczas aktualizacji hasła: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Nie udało się znaleźć użytkownika."]);
    }

    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['repeat_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $repeatPassword = $_POST['repeat_password'];
    if(strlen($newPassword) < 8 || strlen($newPassword) > 20){
        echo json_encode(["success" => false, "message" => "Hasło musi posiadać od 8 do 20 znaków!"]);
        exit();
    }

    if ($newPassword === $repeatPassword) {
        updatePassword($currentPassword, $newPassword);
    } else {
        echo json_encode(["success" => false, "message" => "Nowe hasła nie są identyczne."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe dane wejściowe."]);
}
?>
