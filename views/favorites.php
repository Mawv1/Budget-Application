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

    // Usunięcie budżetu z sesji
    foreach ($_SESSION['favorites'] as $key => $favorite) {
        if ($favorite['budget_id'] === $budgetId) {
            unset($_SESSION['favorites'][$key]); // Usuń element z tablicy
            $_SESSION['favorites'] = array_values($_SESSION['favorites']); // Przebuduj indeksy
            $_SESSION['success'] = "Budżet został usunięty z ulubionych.";
            break;
        }
    }
}

// Pobranie szczegółów ulubionych budżetów
$favorites = [];
if (!empty($_SESSION['favorites'])) {
    foreach ($_SESSION['favorites'] as $favorite) {
        $favorites[] = [
            'budget_id' => $favorite['budget_id'],
            'budget_name' => $favorite['budget_name'],
            'amount_limit' => $favorite['amount_limit'],
            'period_of_time' => $favorite['period_of_time'],
        ];
    }
}

// Maksymalna liczba ulubionych budżetów
$maxFavorites = 3;
if (count($_SESSION['favorites'] ?? []) >= $maxFavorites) {
    $error_message = "Możesz polubić maksymalnie $maxFavorites budżety.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polubione Budżety</title>
    <link rel="stylesheet" href="../landing.css">
    <link rel="icon" type="image/x-icon" href="../pictures/logo.webp">
</head>
<body>
    <header class="header">
        <!-- <div class="comeback">
            <div class="logo-container">
                    <button onclick="window.location.href='../landing_page.php'" class="logo-button">
                        <img src="../pictures/logo.webp" alt="Logo" class="logo">
                        <span class="app-name">BudApp</span>
                    </button>
            </div>
        </div> -->
        <div class="comeback">
            <div class="logo-container">
                <button onclick="window.location.href='../landing_page.php'" class="logo-button">
                    <img src="../pictures/logo.webp" alt="Logo" class="logo">
                    <span class="app-name">BudApp</span>
                </button>
            </div>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="../landing_page.php">Strona główna</a></li>
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
                            <h3><?= htmlspecialchars($budget['budget_name'] ?? 'Nieznany budżet') ?></h3>
                            <p>Limit: <?= isset($budget['amount_limit']) ? number_format((float)$budget['amount_limit'], 2) : 'Brak danych' ?> zł</p>
                            <p>Okres: <?= htmlspecialchars($budget['period_of_time'] ?? 'Brak') ?></p>
                            <!-- Formularz do usuwania budżetu -->
                            <form method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="remove_budget" value="<?= (int)($budget['budget_id'] ?? 0) ?>">
                                <button type="submit" class="remove-btn">Usuń z ulubionych</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- <div class="cta-container">
                    <a href="../landing.php" class="cta-btn">Zobacz więcej budżetów</a>
                </div> -->
                <div class="cta-container">
                    <a href = "../login_module/login.php" class="cta-btn">Zaloguj się, aby zarządzać ulubionymi budżetami.</a>
                </div>
            <?php else: ?>
                <p>Nie masz jeszcze żadnych ulubionych budżetów.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer-favorites">
        <p>&copy; 2025 BudApp. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
