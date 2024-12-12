<?php
session_start();

// Ustaw nagłówek JSON
header('Content-Type: application/json');

// Funkcja do aktualizacji e-maila w bazie danych
function updateEmail($newEmail) {
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "demo";

    // Połączenie z bazą danych
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    $email = mysqli_real_escape_string($conn, $newEmail);
    $userId = $_SESSION['id']; // Załóżmy, że masz ID użytkownika w sesji
    
    $sql = "UPDATE users SET Email = '$email' WHERE User_id = '$userId'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['email'] = $newEmail;
        echo json_encode(["success" => true, "message" => "Email updated successfully!"]);
        header("Location: ../settings.php");
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }

    $conn->close();
}

// Obsługa żądania POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_email']) && isset($_POST['repeat_email'])) {
    $newEmail = $_POST['new_email'];
    $repeatEmail = $_POST['repeat_email'];

    if ($newEmail === $repeatEmail) {
        updateEmail($newEmail);
    } else {
        echo json_encode(["success" => false, "message" => "Email addresses do not match!"]);
    }
}
