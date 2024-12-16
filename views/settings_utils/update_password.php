<?php
session_start();
header('Content-Type: application/json');

function updateEmail($newEmail) {
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "demo";

    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
        exit();
    }

    $email = $conn->real_escape_string($newEmail);
    $userId = $_SESSION['id'];

    $sql = "UPDATE users SET Email = '$email' WHERE User_id = '$userId'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['email'] = $newEmail;
        echo json_encode(["success" => true, "message" => "E-mail został zaktualizowany pomyślnie!", "updated_email" => $newEmail]);
    } else {
        echo json_encode(["success" => false, "message" => "Błąd: " . $conn->error]);
    }

    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_email'], $_POST['repeat_email'])) {
    $newEmail = $_POST['new_email'];
    $repeatEmail = $_POST['repeat_email'];

    if ($newEmail === $repeatEmail) {
        updateEmail($newEmail);
    } else {
        echo json_encode(["success" => false, "message" => "Adresy e-mail nie są identyczne!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe dane wejściowe."]);
}
