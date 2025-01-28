<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}

require_once '../../connect.php';

// Połączenie z bazą danych
$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Sprawdzenie, czy metoda żądania to POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie danych z sesji i formularza
    $user_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
    $budget_name = isset($_POST['budget_name']) ? trim($_POST['budget_name']) : null;
    $amount_limit = isset($_POST['amount_limit']) ? floatval($_POST['amount_limit']) : null;
    $period_of_time = isset($_POST['period_of_time']) ? trim($_POST['period_of_time']) : null;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;

    // Walidacja danych
    $errors = [];
    if (!$user_id) {
        $errors[] = "Nie znaleziono ID użytkownika.";
    }
    if (empty($budget_name)) {
        $errors[] = "Nazwa budżetu nie może być pusta.";
    }
    if ($amount_limit === null || $amount_limit <= 0) {
        $errors[] = "Limit kwoty musi być większy od zera.";
    }
    if (empty($period_of_time)) {
        $errors[] = "Okres musi zostać określony.";
    }
    if (empty($start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date)) {
        $errors[] = "Data rozpoczęcia jest nieprawidłowa.";
    }

    // Jeśli są błędy, wyświetl je i zakończ
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
        exit();
    }

    // Przygotowanie zapytania SQL
    $sql = "INSERT INTO budgets (User_id, budget_name, Amount_limit, Period_of_time, Start_date) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    // Przypisanie wartości do zapytania
    $stmt->bind_param("isdss", $user_id, $budget_name, $amount_limit, $period_of_time, $start_date);

    // Wykonanie zapytania
    if ($stmt->execute()) {
        header('Location: ../budzety.php'); // Przekierowanie na stronę budżetów
        exit();
    } else {
        echo "Błąd: " . $stmt->error;
    }

    // Zamknięcie zapytania i połączenia
    $stmt->close();
    $conn->close();
}
?>
