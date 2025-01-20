<?php
session_start();

if (!isset($_SESSION['logged'])) {
    error_log("Użytkownik niezalogowany próbował usunąć transakcję.");
    echo json_encode(["success" => false, "message" => "Brak dostępu."]);
    exit();
}

header('Content-Type: application/json');

function deleteTransaction($transactionID) {
    require_once '../../connect.php';
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        error_log("Błąd połączenia z bazą danych: " . $conn->connect_error);
        echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
        exit();
    }

    $sql = "DELETE FROM transactions WHERE Transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transactionID);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Transakcja została usunięta."]);
        } else {
            error_log("Nie znaleziono transakcji o ID: $transactionID");
            echo json_encode(["success" => false, "message" => "Nie znaleziono transakcji o ID: $transactionID."]);
        }
    } else {
        error_log("Błąd SQL: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Błąd podczas usuwania transakcji."]);
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transactionID = $_POST['transaction_id'] ?? null;

    if (!$transactionID) {
        error_log("Brak ID transakcji w żądaniu POST.");
        echo json_encode(["success" => false, "message" => "Brak ID transakcji."]);
        exit();
    }

    error_log("Próba usunięcia transakcji z ID: $transactionID");
    deleteTransaction($transactionID);
}
?>
