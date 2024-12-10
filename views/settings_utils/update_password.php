<?php
session_start();

// Funkcja do zmiany hasła w bazie danych
function updatePassword($currentPassword, $newPassword) {
    // Sprawdzenie poprawności obecnego hasła
    require_once "../connect.php";

    // Połączenie z bazą danych
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $currentPassword = mysqli_real_escape_string($conn, $currentPassword);
    $newPassword = mysqli_real_escape_string($conn, $newPassword);
    $userId = $_SESSION['user_id']; // Załóżmy, że masz ID użytkownika w sesji

    // Weryfikacja obecnego hasła
    $sql = "SELECT password FROM users WHERE id = '$userId'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($currentPassword, $row['password'])) {
            // Hasło poprawne, aktualizuj na nowe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";
            if ($conn->query($sql) === TRUE) {
                echo "Password updated successfully!";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Current password is incorrect!";
        }
    }
    $conn->close();
}

// Sprawdzanie danych przesyłanych formularzem
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        updatePassword($currentPassword, $newPassword);
    } else {
        echo "Passwords do not match!";
    }
}
?>
