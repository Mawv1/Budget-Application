<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');
    exit();
}

require_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $admin_id = $_POST['admin_id'];
    $block_reason = $_POST['block_reason'];

    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $sql_block = "INSERT INTO user_blocks (user_id, admin_id, block_reason) VALUES (?, ?, ?)";
    $stmt_block = $conn->prepare($sql_block);
    $stmt_block->bind_param("iis", $user_id, $admin_id, $block_reason);

    if ($stmt_block->execute()) {
        $_SESSION['message'] = "Użytkownik został zablokowany.";
    } else {
        $_SESSION['error'] = "Nie udało się zablokować użytkownika.";
    }

    $stmt_block->close();
    $conn->close();

    header('Location: ../admin_panel.php');
    exit();
}
?>
