<?php
session_start();
require_once 'connect.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Inicjalizacja koszyka ulubionych budżetów w sesji
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Pobranie przykładowych budżetów z tabeli `recommended_budgets`
$sql = "SELECT * FROM recommended_budgets";
$result = $conn->query($sql);

$recommended_budgets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recommended_budgets[] = $row;
    }
}

// Obsługa dodawania do ulubionych w sesji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget_id'])) {
    $budget_id = filter_var($_POST['budget_id'], FILTER_VALIDATE_INT);

    // Pobierz dane budżetu z tabeli
    $stmt = $conn->prepare("SELECT * FROM recommended_budgets WHERE Budget_id = ?");
    $stmt->bind_param("i", $budget_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $budget = $result->fetch_assoc();

        // Sprawdź, czy budżet nie jest już w sesji
        $alreadyInFavorites = false;
        foreach ($_SESSION['favorites'] as $fav) {
            if ($fav['budget_id'] == $budget['Budget_id']) {
                $alreadyInFavorites = true;
                break;
            }
        }

        if (!$alreadyInFavorites) {
            // Dodaj pełne dane budżetu do sesji
            $_SESSION['favorites'][] = [
                'budget_id' => $budget['Budget_id'],
                'budget_name' => $budget['budget_name'],
                'amount_limit' => $budget['Amount_limit'],
                'period_of_time' => $budget['Period_of_time'],
            ];
            $_SESSION['success'] = "Budżet został dodany do ulubionych.";
        } else {
            $_SESSION['error'] = "Ten budżet jest już w ulubionych.";
        }
    } else {
        $_SESSION['error'] = "Nie udało się znaleźć budżetu.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudApp</title>
    <link rel="stylesheet" href="landing.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo.webp">
    <script src="landing.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="pictures/logo.webp" alt="Logo" class="logo">
            <span class="app-name">BudApp</span>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="views/favorites.php">Polubione budżety</a></li>
                <li><a href="login_module/login.php" class="cta-btn">Zaloguj</a></li>
            </ul>
        </nav>
    </header>

    <!-- Początek slidera -->
    <div class="slider">
        <div class="slides">
            <!-- Początek przycisków nawigacyjnych -->
            <input type="radio" name="radio-btn" id="radio1">
            <input type="radio" name="radio-btn" id="radio2">
            <input type="radio" name="radio-btn" id="radio3">
            <!-- Koniec przycisków nawigacyjnych -->

            <!-- Początek zdjęć z zawartością -->
            <div class="slide first">
                <img src="pictures/hero.png" alt="Budżet domowy" class="slide-image">
                <div class="slide-content">
                    <h1>Zarządzaj budżetemi z łatwością</h1>
                    <p>Kontrola wydatków nigdy nie była prostsza.</p>
                    <a href="login_module/register.php" class="cta-button">Rozpocznij za darmo</a>
                </div>
            </div>
            <div class="slide">
                <img src="pictures/savings.png" alt="Planowanie oszczędności" class="slide-image">
                <div class="slide-content">
                    <h1>Planuj swoje wydatki z nami</h1>
                    <p>Twój plan finansowy na wyciągnięcie ręki.</p>
                    <a href="login_module/register.php" class="cta-button">Rozpocznij za darmo</a>
                </div>
            </div>
            <div class="slide">
                <img src="pictures/dream_chasing.png" alt="Oszczędzanie na przyszłość" class="slide-image">
                <div class="slide-content">
                    <h1>Bezpieczna przyszłość finansowa</h1>
                    <p>Oszczędzaj na swoje marzenia.</p>
                    <a href="login_module/register.php" class="cta-button">Rozpocznij za darmo</a>
                </div>
            </div>
            <!-- Koniec zdjęć z zawartością -->

            <!-- Początek automatyznej nawigacji -->
            <div class="navigation-auto">
                <div class="auto-btn1"></div>
                <div class="auto-btn2"></div>
                <div class="auto-btn3"></div>
            </div>
            <!-- Koniec automatyznej nawigacji -->
        </div>

        <!-- Początek ręcznej nawigacji -->
        <div class="navigation-manual">
            <label for="radio1" class="manual-btn"></label>
            <label for="radio2" class="manual-btn"></label>
            <label for="radio3" class="manual-btn"></label>
        </div>
        <!-- Koniec ręcznej nawigacji -->
    </div>
    <!-- Koniec slidera -->

    <section id="features" class="features">
        <h2>Dlaczego warto?</h2>
        <div class="features-grid">
            <div class="feature-item">
                <img src="pictures/feature2.png" alt="Feature 1">
                <h3>Łatwość użycia</h3>
                <p>Intuicyjny interfejs pozwala szybko zarządzać finansami.</p>
            </div>
            <div class="feature-item">
                <img src="pictures/feature3.png" alt="Feature 2">
                <h3>Personalizacja</h3>
                <p>Dostosuj aplikację do swoich potrzeb.</p>
            </div>
            <div class="feature-item">
                <img src="pictures/feature1.png" alt="Feature 3">
                <h3>Analizy i raporty</h3>
                <p>Uzyskaj szczegółowe informacje o swoich wydatkach.</p>
            </div>
        </div>
    </section>

    <!-- <section id="benefits" class="benefits">
        <h2>Korzyści z aplikacji</h2>
        <ul>
            <li>Lepsza kontrola nad budżetem</li>
            <li>Oszczędność czasu i pieniędzy</li>
            <li>Bezpieczeństwo Twoich danych</li>
        </ul>
    </section> -->

    <main>
        <section id="recommended-budgets" class="recommended-budgets">
            <h2>Dodaj do ulubionych, jeśli jakiś budżet pasuje do Ciebie perfekcyjnie</h2>
            <div class="budgets-grid">
                <?php foreach ($recommended_budgets as $budget): ?>
                    <div class="budget-item">
                        <h3><?= htmlspecialchars($budget['budget_name']) ?></h3>
                        <p>Limit: <?= htmlspecialchars($budget['Amount_limit']) ?> zł</p>
                        <p>Okres: <?= htmlspecialchars($budget['Period_of_time'] ?? 'Brak') ?></p>
                        <form method="post">
                            <input type="hidden" name="budget_id" value="<?= $budget['Budget_id'] ?>">
                            <?php if (in_array($budget['Budget_id'], $_SESSION['favorites'])): ?>
                                <button type="submit" class="remove-btn" disabled>Dodano</button>
                            <?php else: ?>
                                <button type="submit" class="add-btn">Dodaj do ulubionych</button>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>


    <footer class="footer">
        <p>&copy; 2025 BudApp. Wszelkie prawa zastrzeżone.</p>
        <a href="#signup" class="signup-footer-link">Załóż konto teraz</a>
    </footer>
</body>
</html>
