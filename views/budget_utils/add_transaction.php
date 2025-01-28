<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}

header('Content-Type: application/json');

require_once '../../connect.php';

function validateInput($data)
{
    $errors = [];

    if ($data['amount'] <= 0) {
        $errors[] = "Kwota musi być większa od zera.";
    }

    if (empty($data['type']) || !in_array($data['type'], ['wydatek', 'przychód'])) {
        $errors[] = "Nieprawidłowy typ transakcji.";
    }

    if (empty($data['date']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date'])) {
        $errors[] = "Nieprawidłowy format daty.";
    }

    if (empty($data['title'])) {
        $errors[] = "Tytuł jest wymagany.";
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
        exit();
    }

    $user_id = $_SESSION['id'];
    $budget_id = intval($_POST['budget_id']);
    $amount = floatval($_POST['amount']);
    $type = $_POST['type'];
    $date = $_POST['date'];
    $category_id = isset($_POST['category']) ? intval($_POST['category']) : null;
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';

    $inputData = compact('amount', 'type', 'date', 'title');
    $errors = validateInput($inputData);

    if (!empty($errors)) {
        echo json_encode(["success" => false, "message" => implode(", ", $errors)]);
        exit();
    }

    $sql = "INSERT INTO transactions (User_id, Budget_id, Amount, Type, Date, Category_id, Title, Description, budget_type)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Błąd przygotowania zapytania SQL: " . $conn->error]);
        $conn->close();
        exit();
    }

    $budget_type = "user";

    $stmt->bind_param(
        "iisssssss",
        $user_id,
        $budget_id,
        $amount,
        $type,
        $date,
        $category_id,
        $title,
        $description,
        $budget_type
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Transakcja została pomyślnie dodana!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Wystąpił błąd podczas dodawania transakcji: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowy typ żądania."]);
}
