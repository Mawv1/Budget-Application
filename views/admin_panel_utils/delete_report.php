<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = $_POST['report_id'];

    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Usunięcie zgłoszenia
    $sql_delete = "DELETE FROM reports WHERE report_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $report_id);

    if ($stmt_delete->execute()) {
        $_SESSION['message'] = "Zgłoszenie zostało usunięte.";
    } else {
        $_SESSION['error'] = "Nie udało się usunąć zgłoszenia.";
    }

    $stmt_delete->close();
    $conn->close();

    header('Location: ../admin_panel.php');
    exit();
}
?>
