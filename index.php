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

// Pobierz ulubione budżety
$sql_favorites = "SELECT b.budget_name, b.Amount_limit, b.Period_of_time, b.Start_date 
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
                        if (isset($_SESSION['profile_picture']) && $_SESSION['profile_picture'] !== null) {
                            echo '<img src="pictures/uploads/' . htmlspecialchars($_SESSION['profile_picture'], ENT_QUOTES) . '" alt="Zdjęcie profilowe" class="profile-pic">';
                        } else {
                            echo '<img src="pictures/user-photo.jpg" alt="User Photo" class="profile-pic">';
                        }
                    ?>
                    <div class="user-profile-dropdown">
                        <div class="user-profile-dropdown-content">
                            <a href="views/settings.php">Ustawienia</a>
                            <a href="login_module/logout.php">Wyloguj</a>
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
            <?php
            echo '<pre>';
            print_r($favorite_budgets);
            echo '</pre>';
            ?>

                <!-- Slider -->
                <div class="slider">
                    <div class="slides">
                        <?php if (!empty($favorite_budgets)): ?>
                            <?php foreach ($favorite_budgets as $index => $budget): ?>
                                <div class="slide <?= $index === 0 ? 'first' : '' ?>">
                                    <div class="slide-content">
                                        <h1><?= htmlspecialchars($budget['budget_name']) ?></h1>
                                        <p>Limit: <?= htmlspecialchars($budget['Amount_limit']) ?> zł</p>
                                        <p>Okres: <?= htmlspecialchars($budget['Period_of_time']) ?></p>
                                        <p>Data rozpoczęcia: <?= htmlspecialchars($budget['Start_date']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="slide first">
                                <div class="slide-content">
                                    <h1>Brak ulubionych budżetów</h1>
                                    <p>Dodaj swoje ulubione budżety, aby je tutaj zobaczyć!</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Nawigacja manualna -->
                    <div class="navigation-manual">
                        <?php foreach ($favorite_budgets as $index => $budget): ?>
                            <label for="radio<?= $index + 1 ?>" class="manual-btn"></label>
                        <?php endforeach; ?>
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
                echo '<li><a href="#">Kontakt</a></li>';
            echo '</ul>';
        echo '</footer>';
    ?>
</body>
</html>
