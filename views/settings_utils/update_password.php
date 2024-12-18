<?php
session_start();
header('Content-Type: application/json');

// Funkcja do aktualizacji hasła
function updatePassword($currentPassword, $newPassword, $confirmPassword) {
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

    if (!isset($_SESSION['email'])) {
        echo json_encode(["success" => false, "message" => "Użytkownik nie jest zalogowany."]);
        exit();
    }

    $email = $_SESSION['email'];
    $emailEscaped = $conn->real_escape_string($email);

    // Pobierz aktualne hasło z bazy danych
    $sql = "SELECT password FROM users WHERE email = '$emailEscaped'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        if (!password_verify($currentPassword, $hashedPassword)) {
            echo json_encode(["success" => false, "message" => "Obecne hasło jest nieprawidłowe."]);
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode(["success" => false, "message" => "Nowe hasła nie są identyczne."]);
            exit();
        }

        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Aktualizacja hasła w bazie danych
        $updateSql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ss", $newPasswordHash, $email);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Hasło zostało pomyślnie zmienione."]);
        } else {
            error_log("SQL error: " . $conn->error);
            echo json_encode(["success" => false, "message" => "Błąd podczas aktualizacji hasła."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Nie znaleziono użytkownika."]);
    }

    $conn->close();
}

// Obsługa żądania POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    updatePassword($currentPassword, $newPassword, $confirmPassword);
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe dane wejściowe."]);
}
