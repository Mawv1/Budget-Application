<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}

header('Content-Type: application/json');

function removeBudget($budgetID) {
    require_once '../../connect.php';
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        error_log("Database connection error: " . $conn->connect_error);
        echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
        exit();
    }

    $sql = "DELETE FROM budgets WHERE Budget_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $budgetID);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Budżet został pomyślnie usunięty!"]);
    } else {
        error_log("SQL error: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Błąd podczas usuwania budżetu."]);
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $budgetID = $_POST['budget_id'] ?? null;

    if (!$budgetID) {
        error_log('Brak ID budżetu w żądaniu POST');
        echo json_encode(["success" => false, "message" => "Brak ID budżetu."]);
        exit();
    }

    error_log('Otrzymano żądanie usunięcia budżetu z ID: ' . $budgetID);
    removeBudget($budgetID);
}

?>
