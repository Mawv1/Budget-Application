<?php
session_start();

// Funkcja do aktualizacji e-maila w bazie danych
function updateEmail($newEmail) {
    // require_once "connect.php";
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "demo";

    // Połączenie z bazą danych
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $email = mysqli_real_escape_string($conn, $newEmail);
    $userId = $_SESSION['id']; // Załóżmy, że masz ID użytkownika w sesji
    
    $sql = "UPDATE users SET Email = '$email' WHERE User_id = '$userId'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['email'] = $newEmail; // Aktualizowanie e-maila w sesji
        echo "Email updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}

// Sprawdzenie, czy formularz jest wysyłany
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_email']) && isset($_POST['repeat_email'])) {
    $newEmail = $_POST['new_email'];
    $repeatEmail = $_POST['repeat_email'];

    if ($newEmail === $repeatEmail) {
        updateEmail($newEmail);
    } else {
        echo "Email addresses do not match!";
    }
}
?>
