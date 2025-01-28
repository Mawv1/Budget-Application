<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: login_module/login.php');
    exit();
}

require_once 'connect.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$user_id = $_SESSION['id']; // ID zalogowanego użytkownika

$sql_check_block = "SELECT * FROM user_blocks WHERE user_id = ?";
$stmt_check_block = $conn->prepare($sql_check_block);
$stmt_check_block->bind_param("i", $_SESSION['id']);
$stmt_check_block->execute();
$result_check_block = $stmt_check_block->get_result();

if ($result_check_block->num_rows > 0) {
    header('Location: blocked_notification.php');
    exit();
}

// Pobierz ulubione budżety
$sql_favorites = "SELECT b.budget_name, b.Amount_limit, b.Period_of_time, b.Start_date, b.Budget_id 
                  FROM favorite_budgets fb
                  JOIN budgets b ON fb.budget_id = b.Budget_id
                  WHERE fb.user_id = ?
                  LIMIT 3";
$stmt_favorites = $conn->prepare($sql_favorites);
$stmt_favorites->bind_param("i", $user_id);
$stmt_favorites->execute();
$result_favorites = $stmt_favorites->get_result();

$favorite_budgets = [];
if ($result_favorites->num_rows > 0) {
    while ($row = $result_favorites->fetch_assoc()) {
        $favorite_budgets[] = $row; // Zapisujemy wyniki w tablicy
    }
}
$stmt_favorites->close();

$transactions_by_budget = [];
$income_by_budget = [];

foreach ($favorite_budgets as $budget) {
    $budget_id = $budget['Budget_id']; // Zakładam, że kolumna budget_id jest dostępna w wynikach ulubionych budżetów

    $sql_transactions_for_budget = "
        SELECT t.Transaction_id, t.Amount, t.Date, c.Category_name
        FROM transactions t
        JOIN categories c ON t.Category_id = c.Category_id
        WHERE t.budget_id = ? AND t.Type = 'wydatek'
    ";
    $stmt_transactions_for_budget = $conn->prepare($sql_transactions_for_budget);
    $stmt_transactions_for_budget->bind_param("i", $budget_id);
    $stmt_transactions_for_budget->execute();
    $result_transactions_for_budget = $stmt_transactions_for_budget->get_result();

    $transactions = [];
    if ($result_transactions_for_budget->num_rows > 0) {
        while ($row = $result_transactions_for_budget->fetch_assoc()) {
            $transactions[] = $row;
        }
    }
    $transactions_by_budget[$budget_id] = $transactions; // Klucz to ID budżetu
    $stmt_transactions_for_budget->close();

    $sql_income_for_budget = "
    SELECT t.Transaction_id, t.Amount, t.Date, c.Category_name
    FROM transactions t
    JOIN categories c ON t.Category_id = c.Category_id
    WHERE t.budget_id = ? AND t.Type = 'przychód'
    ";

    $stmt_income_for_budget = $conn->prepare($sql_income_for_budget);
    $stmt_income_for_budget->bind_param("i", $budget_id);
    $stmt_income_for_budget->execute();
    $results_income_for_budget = $stmt_income_for_budget->get_result();

    $income = [];
    if($results_income_for_budget->num_rows > 0){
        while($row = $results_income_for_budget->fetch_assoc()){
            $income[] = $row;
        }
    }

    $income_by_budget[$budget_id] = $income;
    $stmt_income_for_budget->close();
}

$sql_user = "SELECT is_admin FROM users WHERE User_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$is_admin = $user_data['is_admin'];
if ($is_admin){
    $_SESSION['is_admin'] = $user_data['is_admin'];
}
$stmt_user->close();

$has_pending_reports = false;

if ($is_admin) {
    $sql_pending_reports = "SELECT COUNT(*) AS pending_count FROM reports WHERE is_done = 0";
    $result_pending_reports = $conn->query($sql_pending_reports);
    if ($result_pending_reports) {
        $row = $result_pending_reports->fetch_assoc();
        $has_pending_reports = $row['pending_count'] > 0;
    }
}

$sql_user_profile_picture = "SELECT profile_picture FROM users WHERE User_id = ?";
$stmt_user_profile_picture = $conn->prepare($sql_user_profile_picture);
$stmt_user_profile_picture->bind_param("i", $user_id);
$stmt_user_profile_picture->execute();
$result_user_profile_picture = $stmt_user_profile_picture->get_result();
$user_profile_picture = $result_user_profile_picture->fetch_assoc();
$stmt_user_profile_picture->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudApp</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo.webp">
    <script src="slider.js"></script>
    <script src="logout.js"></script>
    <script>
        window.transactionsByCategory<?= $index ?> = <?= json_encode($transactions_by_budget[$budget['Budget_id']] ?? []) ?>;
        window.transactionsByDate<?= $index ?> = <?= json_encode($transactions_by_budget[$budget['Budget_id']] ?? []) ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Nagłówek strony -->
    <header class="header">
        <div class="logo-container">
            <img src="pictures/logo.webp" alt="Logo" class="logo">
            <span class="app-name">BudApp</span>
        </div>
        <div class="user-controls">
            <div class="user-greeting">
                <?php
                    echo "<span>Witaj ".$_SESSION['name']."!</span>";
                ?>
            </div>
            <div class="user-profile">
                <button class="profile-button">
                    <?php
                    if (isset($_SESSION['profile_picture']) && $_SESSION['profile_picture'] !== null && $user_profile_picture['profile_picture'] !== null) {    
                        echo '<img src="pictures/uploads/' . htmlspecialchars($_SESSION['profile_picture'], ENT_QUOTES) . '" alt="Zdjęcie profilowe" class="profile-pic">';
                    } else {
                        echo '<img src="pictures/user-photo.jpg" alt="User Photo" class="profile-pic">';
                    }
                    ?>
                    <?php if ($has_pending_reports): ?>
                        <div class="notification-dot"></div>
                    <?php endif; ?>
                    <div class="user-profile-dropdown">
                        <div class="user-profile-dropdown-content">
                            <a href="views/settings.php">Ustawienia</a>
                            <a href="login_module/logout.php">Wyloguj</a>
                            <?php if ($is_admin): ?>
                                <a href="views/admin_panel.php" class="admin-panel-button">
                                    <span>Panel Administratora</span>
                                    <?php if ($has_pending_reports): ?>
                                        <div class="notification-dot"></div>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </header>
    
    <div class="wrapper">
        <!-- Główna zawartość strony -->
        <main class="content">
            <div class="main-container">

                <!-- Slider -->
                <div class="slider">
                    <div class="slides">
                        <?php for ($i = 0; $i < count($favorite_budgets); $i++): ?>
                            <input type="radio" name="radio-btn" id="radio<?= $i + 1 ?>">
                        <?php endfor; ?>
                        <?php if (!empty($favorite_budgets)): ?>
                            <?php foreach ($favorite_budgets as $index => $budget): ?>
                                <div class="slide <?= $index === 0 ? 'first' : '' ?>">
                                    <div class="slide-content">
                                        <h1><?= htmlspecialchars($budget['budget_name']) ?></h1>
                                        <p>Limit: <?= htmlspecialchars($budget['Amount_limit']) ?> zł</p>
                                        <p>Okres: <?= htmlspecialchars($budget['Period_of_time']) ?></p>
                                        <p>Data rozpoczęcia: <?= htmlspecialchars($budget['Start_date']) ?></p>
                                        <form action="views/budget_utils/budgets_details.php" method="get" style="display:inline;">
                                            <input type="hidden" name="budget_id" value="<?= $budget['Budget_id'] ?>">
                                            <button type="submit" class="details-btn">Zobacz szczegóły</button>
                                        </form>
                                    </div>
                                    <div class="slide-chart">
                                        <?php if (!empty($transactions_by_budget[$budget['Budget_id']])): ?>
                                            <!-- Wykres wydatków -->
                                            <p>Wydatki</p>
                                            <canvas id="chart-expense-<?= $index ?>"></canvas>
                                        <?php else: ?>
                                            <p>Brak wydatków</p>
                                        <?php endif; ?>

                                        <?php if (!empty($income_by_budget[$budget['Budget_id']])): ?>
                                            <!-- Wykres przychodów -->
                                            <p>Przychody</p>
                                            <canvas id="chart-income-<?= $index ?>"></canvas>
                                        <?php else: ?>
                                            <p>Brak przychodów</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <script>
                                    window.transactionsByCategory<?= $index ?> = <?= json_encode($transactions_by_budget[$budget['Budget_id']] ?? []); ?>;
                                    window.transactionsByCategory1<?= $index ?> = <?= json_encode($income_by_budget[$budget['Budget_id']] ?? []); ?>;
                                </script>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="slide first">
                                <div class="slide-content">
                                    <h1>Brak ulubionych budżetów</h1>
                                    <p>Dodaj swoje ulubione budżety, aby je tutaj zobaczyć!</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- Nawigacja automatyczna -->
                        <div class="navigation-auto">
                            <?php for ($i = 0; $i < count($favorite_budgets); $i++): ?>
                                <div class="auto-btn<?= $i + 1?>"></div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Nawigacja manualna -->
                    <div class="navigation-manual">
                        <?php for ($i = 0; $i < count($favorite_budgets); $i++): ?>
                            <label for="radio<?= $i + 1 ?>" class="manual-btn"></label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="button-container">
                    <a href="index.php" class="button">
                        <img src="icons/desktop_icon.png" alt="Desktop Icon">
                        <span>Pulpit</span>
                    </a>
                    <a href="views/budzety.php" class="button">
                        <img src="icons/budget_icon.png" alt="Budget Icon">
                        <span>Budżety</span>
                    </a>
                    <a href="views/nowa_grupa.php" class="button">
                        <img src="icons/groups_icon.png" alt="Group Icon">
                        <span>Grupy</span>
                    </a>
                    <a href="views/analiza_finansowa.php" class="button">
                        <img src="icons/analysis_icon.png" alt="Analysis Icon">
                        <span>Analiza Finansowa</span>
                    </a>
                    <a href="views/nagrody.php" class="button">
                        <img src="icons/reward_icon.png" alt="Reward Icon">
                        <span>Nagrody</span>
                    </a>
                </div>
        
            </div>
        </main>
    </div>
    <?php
        $current_year = date("Y");
        echo '<footer class="footer">';
            echo '<p>&copy; '.$current_year.' BudApp. Wszelkie prawa zastrzeżone.</p>';
            echo '<ul class="footer-links">';
                echo '<li><a href="#">Polityka prywatności</a></li>';
                echo '<li><a href="#">Regulamin</a></li>';
                echo '<li><a href="views/contact.php">Kontakt</a></li>';
            echo '</ul>';
        echo '</footer>';
    ?>
</body>
</html>
