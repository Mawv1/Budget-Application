<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');
    exit();
}

require_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'])) {
    $report_id = intval($_POST['report_id']);
    $is_done = isset($_POST['is_done']) ? 1 : 0;

    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE reports SET is_done = ? WHERE report_id = ?");
    $stmt->bind_param("ii", $is_done, $report_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    header('Location: ../admin_panel.php');
    exit();
}
?>
