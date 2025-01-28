<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}

header('Content-Type: application/json');

function removeBudget($userID, $budgetID) {
    require_once '../../connect.php';
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        error_log("Database connection error: " . $conn->connect_error);
        echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
        exit();
    }

    // Usuwanie transakcji z budzetu
    $sql_remove_transactions = "DELETE FROM transactions WHERE User_id = ? AND Budget_id = ?";
    $stmt_remove_transactions = $conn->prepare($sql_remove_transactions);
    $stmt_remove_transactions->bind_param("ii", $userID, $budgetID);
    if($stmt_remove_transactions->execute()){
        $sql = "DELETE FROM budgets WHERE User_id = ? AND Budget_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userID, $budgetID);
    
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Budżet został pomyślnie usunięty!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Błąd podczas usuwania budżetu."]);
        }
    
        $stmt->close();
    }
    else{
        echo json_encode(["success" => false, "message" => "Błąd podczas usuwania transakcji."]);
    }

    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $budgetID = $_POST['budget_id'] ?? null;
    $userID = $_SESSION['id'] ?? null;

    if(!$userID) {
        error_log('Brak ID użytkownika w sesji');
        echo json_encode(["success" => false, "message" => "Brak ID użytkownika."]);
        exit();
    }

    if (!$budgetID) {
        error_log('Brak ID budżetu w żądaniu POST');
        echo json_encode(["success" => false, "message" => "Brak ID budżetu."]);
        exit();
    }

    removeBudget($userID,$budgetID);
}

?>
