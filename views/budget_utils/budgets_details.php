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

if (!isset($_GET['budget_id']) || empty($_GET['budget_id'])) {
    die("Nieprawidłowy budżet.");
}

$budget_id = intval($_GET['budget_id']);

$sql_budget = "SELECT * FROM budgets WHERE Budget_id = ?";
$stmt_budget = $conn->prepare($sql_budget);
$stmt_budget->bind_param("i", $budget_id);
$stmt_budget->execute();
$result_budget = $stmt_budget->get_result();
$budget = $result_budget->fetch_assoc();

if (!$budget) {
    die("Nie znaleziono budżetu.");
}

$sql_transactions = "SELECT t.*, c.Category_name 
                     FROM transactions t 
                     LEFT JOIN categories c ON t.Category_id = c.Category_id 
                     WHERE t.User_id = ? AND t.Budget_id = ?";
$stmt_transactions = $conn->prepare($sql_transactions);
$stmt_transactions->bind_param("ii", $_SESSION['id'], $budget_id);
$stmt_transactions->execute();
$result_transactions = $stmt_transactions->get_result();

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

$sql_totals = "SELECT 
    SUM(CASE WHEN Type = 'wydatek' THEN Amount ELSE 0 END) AS total_expenses,
    SUM(CASE WHEN Type = 'przychód' THEN Amount ELSE 0 END) AS total_income
    FROM transactions WHERE User_id = ? AND Budget_id = ?";
$stmt_totals = $conn->prepare($sql_totals);
$stmt_totals->bind_param("ii", $_SESSION['id'], $budget_id);
$stmt_totals->execute();
$result_totals = $stmt_totals->get_result();
$totals = $result_totals->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Szczegóły Budżetu</title>
    <link rel="stylesheet" href="../../styles/style.css">
    <link rel="stylesheet" href="../../styles/budgets_styles/budgets_style.css">
    <script src="add_transaction.js" defer></script>
    <script src="delete_transaction.js" defer></script>
</head>
<body>
<div class="wrapper">
    <h1>Szczegóły Budżetu: <?php echo htmlspecialchars($budget['budget_name']); ?></h1>
    <div class="budget-info">
        <p>Limit: <span class="highlight"><?php echo htmlspecialchars($budget['Amount_limit']); ?> zł</span></p>
        <p>Okres: <?php echo htmlspecialchars($budget['Period_of_time']); ?></p>
        <p>Data rozpoczęcia: <?php echo htmlspecialchars($budget['Start_date']); ?></p>
        <p>Wydatki: <span class="highlight"><?php echo number_format($totals['total_expenses'], 2); ?> zł</span></p>
        <p>Przychody: <span class="highlight"><?php echo number_format($totals['total_income'], 2); ?> zł</span></p>
    </div>

    <h2>Transakcje</h2>
    <?php if ($result_transactions->num_rows > 0): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Kwota</th>
                    <th>Typ</th>
                    <th>Data</th>
                    <th>Kategoria</th>
                    <th>Tytuł</th>
                    <th>Opis</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transaction = $result_transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['Amount']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['Type']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['Date']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['Category_name'] ?? 'Brak kategorii'); ?></td>
                        <td><?php echo htmlspecialchars($transaction['Title']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['Description']); ?></td>
                        <td>
                            <form action="delete_transaction.php" method="post" class="deleteTransactionForm">
                                <input type="hidden" name="transaction_id"  value="<?php echo htmlspecialchars($transaction['Transaction_id']); ?>">
                                <button type="button" class="delete-transaction-btn">Usuń</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Brak wydatków dla tego budżetu.</p>
    <?php endif; ?>

    <h2>Dodaj Transakcje</h2>
    <form action="add_transaction.php" method="post" class="add-expense">
        <input type="hidden" name="budget_id" value="<?php echo $budget_id; ?>">
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
            <?php while ($category = $result_categories->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($category['Category_id']); ?>">
                    <?php echo htmlspecialchars($category['Category_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <label for="title">Tytuł:</label>
        <input type="text" name="title" id="title" required>
        <label for="description">Opis:</label>
        <textarea name="description" id="description"></textarea>
        <button type="submit">Dodaj</button>
    </form>

    <!-- Powiadomienie -->
    <div id="transactionNotification" class="notification hidden">
        <span id="transactionNotificationMessage"></span>
        <button id="closeTransactionNotification">X</button>
    </div>

    <!-- Modal potwierdzenia usunięcia -->
    <div id="deleteTransactionModal" class="modal">
        <div class="modal-content">
            <h3>Potwierdź usunięcie transakcji</h3>
            <p>Czy na pewno chcesz usunąć tę transakcję?</p>
            <button id="confirmTransactionDelete">Tak</button>
            <button id="cancelTransactionDelete">Anuluj</button>
        </div>
    </div>

    <!-- Powiadomienie -->
    <div id="transactionDeleteNotification" class="notification hidden">
        <span id="notificationMessage"></span>
        <button id="closeNotification">X</button>
    </div>
</div>
</body>
</html>
