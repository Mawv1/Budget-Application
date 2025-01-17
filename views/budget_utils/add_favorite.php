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
        // Znajdź najstarszy rekord dla użytkownika
        $sql_oldest = "SELECT budget_id FROM favorite_budgets WHERE user_id = ? ORDER BY added_at ASC LIMIT 1";
        $stmt_oldest = $conn->prepare($sql_oldest);
        $stmt_oldest->bind_param("i", $user_id);
        $stmt_oldest->execute();
        $result_oldest = $stmt_oldest->get_result();
    
        if ($result_oldest->num_rows > 0) {
            $oldest = $result_oldest->fetch_assoc();
            $oldest_budget_id = $oldest['budget_id'];
    
            // Usuń najstarszy rekord
            $sql_remove = "DELETE FROM favorite_budgets WHERE user_id = ? AND budget_id = ?";
            $stmt_remove = $conn->prepare($sql_remove);
            $stmt_remove->bind_param("ii", $user_id, $oldest_budget_id);
            $stmt_remove->execute();
        }
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
