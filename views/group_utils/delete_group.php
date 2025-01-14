<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../login_module/login.php');
    exit();
}

require_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = intval($_POST['group_id']);

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $sql = "DELETE FROM groups WHERE group_id = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $group_id, $_SESSION['id']);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Grupa została pomyślnie usunięta.";
    } else {
        $_SESSION['error'] = "Wystąpił błąd podczas usuwania grupy.";
    }

    $stmt->close();
    $conn->close();

    header('Location: ../nowa_grupa.php');
    exit();
}
?>
