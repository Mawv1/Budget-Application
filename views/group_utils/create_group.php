<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../login_module/login.php');
    exit();
}

require_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id']; // ID zalogowanego użytkownika
    $group_name = trim($_POST['group_name']);
    $group_budget = floatval($_POST['group_budget']);

    if (empty($group_name) || $group_budget <= 0) {
        $_SESSION['error'] = "Nazwa grupy nie może być pusta, a budżet musi być większy od zera.";
        header('Location: ../nowa_grupa.php');
        exit();
    }

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $sql = "INSERT INTO groups (group_name, budget_limit, created_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $group_name, $group_budget, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Grupa została pomyślnie utworzona.";
    } else {
        $_SESSION['error'] = "Wystąpił błąd podczas tworzenia grupy.";
    }

    $stmt->close();
    $conn->close();

    header('Location: ../nowa_grupa.php');
    exit();
}
?>
