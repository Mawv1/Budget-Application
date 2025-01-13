<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}

require_once '../../connect.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $budget_id = intval($_POST['budget_id']);

    // Sprawdzenie, ile ulubionych budżetów już istnieje
    $sql_check = "SELECT COUNT(*) AS count FROM favorite_budgets WHERE user_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $count = $result_check->fetch_assoc()['count'];

    if ($count >= 3) {
        $_SESSION['error'] = "Możesz dodać maksymalnie 3 ulubione budżety.";
        header('Location: ../budzety.php');
        exit();
    }

    // Dodanie budżetu do ulubionych
    $sql_add = "INSERT INTO favorite_budgets (user_id, budget_id) VALUES (?, ?)";
    $stmt_add = $conn->prepare($sql_add);
    $stmt_add->bind_param("ii", $user_id, $budget_id);

    if ($stmt_add->execute()) {
        $_SESSION['success'] = "Budżet został dodany do ulubionych.";
    } else {
        $_SESSION['error'] = "Wystąpił błąd podczas dodawania budżetu do ulubionych.";
    }

    header('Location: ../budzety.php');
    exit();
}
?>
