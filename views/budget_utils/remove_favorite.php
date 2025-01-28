<?php
session_start();


if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}

header('Content-Type: application/json');

function removeFavoriteBudget($userID, $budgetID) {
    require_once '../../connect.php';
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        error_log("Błąd połączenia z bazą danych: " . $conn->connect_error);
        echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
        exit();
    }

    $sql = "DELETE FROM favorite_budgets WHERE user_id = ? AND budget_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Błąd przygotowania zapytania SQL: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Błąd serwera."]);
        exit();
    }

    $stmt->bind_param("ii", $userID, $budgetID);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Budżet został usunięty z ulubionych."]);
    } else {
        error_log("Błąd wykonania zapytania SQL: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Błąd podczas usuwania budżetu."]);
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['id'] ?? null;
    $budgetID = $_POST['budget_id'] ?? null;

    if (!$userID || !$budgetID) {
        error_log('Nieprawidłowe dane wejściowe: userID=' . $userID . ', budgetID=' . $budgetID);
        echo json_encode(["success" => false, "message" => "Brak danych wejściowych."]);
        exit();
    }

    removeFavoriteBudget($userID, $budgetID);
}
?>
