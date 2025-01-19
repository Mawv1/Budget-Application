<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../login_module/login.php');
    exit();
}

require_once '../connect.php';

$user_id = $_SESSION['id'];

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Pobranie budżetów użytkownika
$sql = "SELECT Budget_id, budget_name, Amount_limit, Period_of_time, Start_date 
        FROM budgets 
        WHERE User_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje Budżety</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/budgets_styles/budgets_style.css">
    <script src="budget_utils/remove_budget.js"></script>
</head>
<body>
    <header class="header">
        <a href="../index.php" class="app-name">Powrót do strony głównej</a>
        <h1>Twoje Budżety</h1>
    </header>

    <main class="content">
        <!-- Lista Budżetów -->
        <section class="widget budget-list">
            <h2>Twoje Budżety</h2>
            <?php if ($result->num_rows > 0): ?>
                <ul class="budget-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="budget-item">
                            <article class="budget-summary">
                                <!-- Dodaj trim !!! -->
                                <strong><?= htmlspecialchars($row['budget_name']) ?></strong>
                                <p>Limit: <?= htmlspecialchars($row['Amount_limit']) ?> zł</p>
                                <p>Okres: <?= htmlspecialchars($row['Period_of_time']) ?></p>
                                <p>Data rozpoczęcia: <?= htmlspecialchars($row['Start_date']) ?></p>
                                <form action="budget_utils/budgets_details.php" method="get" style="display:inline;">
                                    <input type="hidden" name="budget_id" value="<?= $row['Budget_id'] ?>">
                                    <button type="submit" class="details-btn">Zobacz szczegóły</button>
                                </form>
                                <form action="budget_utils/add_favorite.php" method="post" style="display:inline;">
                                    <input type="hidden" name="budget_id" value="<?= $row['Budget_id'] ?>">
                                    <button type="submit" class="favorite-btn">Dodaj do ulubionych</button>
                                </form>
                                <form action="budget_utils/remove_budget.php" method="post" class="removeBudgetForm" style="display:inline">
                                    <input type="hidden" name="budget_id" value="<?= htmlspecialchars($row['Budget_id']) ?>">
                                    <button type="button" class="remove-btn">Usuń budżet</button>
                                </form>
                            </article>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nie masz jeszcze żadnych budżetów.</p>
            <?php endif; ?>
        </section>
            
        <!-- Modal potwierdzenia usunięcia budżetu -->
        <div id="removeBudgetModal" class="modal">
            <div class="modal-content">
                <h3>Potwierdź usunięcie budżetu</h3>
                <p>Czy na pewno chcesz usunąć ten budżet?</p>
                <button id="confirmBudgetRemove">Tak</button>
                <button id="cancelBudgetRemove">Anuluj</button>
            </div>
        </div>

        <!-- Powiadomienie -->
        <div id="budgetRemoveNotification" class="notification hidden">
            <span id="notificationMessage"></span>
            <button id="closeNotification">X</button>
        </div>

        <!-- Formularz Dodawania Budżetu -->
        <section class="widget add-budget">
            <h2>Dodaj Nowy Budżet</h2>
            <form action="budget_utils/add_budget.php" method="post">
                <label for="budget_name">Nazwa Budżetu:</label>
                <input type="text" id="budget_name" name="budget_name" placeholder="Wpisz nazwę budżetu" required>

                <label for="amount_limit">Limit Kwoty:</label>
                <input type="number" id="amount_limit" name="amount_limit" placeholder="np. 5000" required>

                <label for="period_of_time">Okres:</label>
                <input type="text" id="period_of_time" name="period_of_time" placeholder="np. miesięczny, roczny" required>

                <label for="start_date">Data Rozpoczęcia:</label>
                <input type="date" id="start_date" name="start_date" required>

                <button type="submit">Dodaj Budżet</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <?= date("Y") ?> BudApp. Wszelkie prawa zastrzeżone.</p>
        <ul class="footer-links">
            <li><a href="#">Polityka prywatności</a></li>
            <li><a href="#">Regulamin</a></li>
            <li><a href="#">Kontakt</a></li>
        </ul>
    </footer>
</body>
</html>
