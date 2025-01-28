<?php
session_start();
require_once '../connect.php';

// Połączenie z bazą danych
$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Obsługa usuwania budżetu z ulubionych
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_budget'])) {
    $budgetId = (int)$_POST['remove_budget'];
    if (($key = array_search($budgetId, $_SESSION['favorites'])) !== false) {
        unset($_SESSION['favorites'][$key]); // Usuwanie budżetu z sesji
        $_SESSION['favorites'] = array_values($_SESSION['favorites']); // Przebudowa indeksów
    }
}

// Pobranie szczegółów ulubionych budżetów
$favorites = [];
if (!empty($_SESSION['favorites'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['favorites']), '?'));
    $query = "SELECT * FROM recommended_budgets WHERE Budget_id IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('i', count($_SESSION['favorites'])), ...$_SESSION['favorites']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
}

// Maksymalna liczba ulubionych budżetów
$maxFavorites = 3;
if (count($_SESSION['favorites'] ?? []) >= $maxFavorites) {
    $error_message = "Możesz polubić maksymalnie $maxFavorites budżety.";
}
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polubione Budżety</title>
    <link rel="stylesheet" href="../landing.css">
</head>
<body>
    <header class="header">
        <div class="comeback">
                <button class="back-button" onclick="window.location.href='../landing_page.php'">
                    <img src="../pictures/logo.webp" alt="Logo" class="logo">
                    <span class="app-name">BudApp</span>
                </button>
            </div>
        <nav class="nav">
            <ul>
                <li><a href="../landing.php">Strona główna</a></li>
                <li><a href="../login_module/login.php" class="cta-btn">Zaloguj</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <section id="favorites">
            <h2>Twoje Polubione Budżety</h2>
            <?php if (!empty($favorites)): ?>
                <?php if (isset($error_message)): ?>
                    <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
                <?php endif; ?>
                <div class="budgets-grid">
                    <?php foreach ($favorites as $budget): ?>
                        <div class="budget-item">
                            <h3><?= htmlspecialchars($budget['budget_name']) ?></h3>
                            <p>Limit: <?= htmlspecialchars($budget['Amount_limit']) ?> zł</p>
                            <p>Okres: <?= htmlspecialchars($budget['Period_of_time'] ?? 'Brak') ?></p>
                            <!-- Formularz do usuwania budżetu -->
                            <form method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="remove_budget" value="<?= htmlspecialchars($budget['Budget_id']) ?>">
                                <button type="submit" class="remove-btn">Usuń z ulubionych</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nie masz jeszcze żadnych ulubionych budżetów.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 BudApp. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
