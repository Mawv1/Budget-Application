<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');
    exit();
}

require_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Usuń blokadę użytkownika
    $sql_unblock = "DELETE FROM user_blocks WHERE user_id = ?";
    $stmt_unblock = $conn->prepare($sql_unblock);
    $stmt_unblock->bind_param("i", $user_id);

    if ($stmt_unblock->execute()) {
        $_SESSION['message'] = "Użytkownik został odblokowany.";
    } else {
        $_SESSION['error'] = "Nie udało się odblokować użytkownika.";
    }

    $stmt_unblock->close();
    $conn->close();

    header('Location: ../admin_panel.php');
    exit();
}
?>
