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

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $budget_id = intval($_POST['budget_id']);
    $amount = floatval($_POST['amount']);
    $type = $_POST['type'];
    $date = $_POST['date'];
    $category_id = intval($_POST['category']); // ID kategorii z formularza
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Walidacja danych wejściowych
    if ($amount <= 0) {
        $errors[] = "Kwota musi być większa od zera.";
    }
    if (empty($type) || !in_array($type, ['wydatek', 'przychód'])) {
        $errors[] = "Nieprawidłowy typ transakcji.";
    }
    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = "Nieprawidłowy format daty.";
    }
    if (empty($title)) {
        $errors[] = "Tytuł jest wymagany.";
    }

    // Jeśli nie ma błędów, wykonaj zapytanie
    if (empty($errors)) {
        $sql = "INSERT INTO transactions (User_id, Budget_id, Amount, Type, Date, Category_id, Title, Description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssss", $user_id, $budget_id, $amount, $type, $date, $category_id, $title, $description);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Wystąpił błąd podczas dodawania transakcji: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Transakcję</title>
    <link rel="stylesheet" href="../../styles/styles.css">
    <link rel="stylesheet" href="../../styles/budgets_styles/budgets_style.css">
</head>
<body>
<div class="wrapper">
    <h1>Dodaj Transakcję</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <p>Transakcja została pomyślnie dodana!</p>
            <a href="budget_details.php?budget_id=<?= $budget_id ?>" class="button">Powrót do szczegółów budżetu</a>
        </div>
    <?php else: ?>
        <form action="add_transaction.php" method="post" class="add-expense">
            <input type="hidden" name="budget_id" value="<?= htmlspecialchars($budget_id) ?>">
            <label for="amount">Kwota:</label>
            <input type="number" step="0.01" name="amount" id="amount" required>
            <label for="type">Typ:</label>
            <select name="type" id="type" required>
                <option value="wydatek">Wydatek</option>
                <option value="przychód">Przychód</option>
            </select>
            <label for="date">Data:</label>
            <input type="date" name="date" id="date" required>
            <label for="category">Kategoria:</label>
            <select name="category" id="category">
                <option value="jedzenie">Jedzenie</option>
                <option value="transport">Transport</option>
                <option value="rozrywka">Rozrywka</option>
                <option value="inne">Inne</option>
            </select>
            <label for="title">Tytuł:</label>
            <input type="text" name="title" id="title" required>
            <label for="description">Opis:</label>
            <textarea name="description" id="description"></textarea>
            <button type="submit">Dodaj</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
