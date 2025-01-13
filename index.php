<?php
    session_start();

    if(!isset($_SESSION['logged'])){
        // użytkownik nie jest zalogowany
        header('Location: ../login_module/login.php');
        exit();
    }
?>

<?php
require_once 'connect.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$user_id = $_SESSION['id']; // ID zalogowanego użytkownika

$sql_favorites = "SELECT b.budget_name, b.Amount_limit, b.Period_of_time, b.Start_date 
                  FROM favorite_budgets fb
                  JOIN budgets b ON fb.budget_id = b.Budget_id
                  WHERE fb.user_id = ?
                  LIMIT 3";
$stmt_favorites = $conn->prepare($sql_favorites);
$stmt_favorites->bind_param("i", $user_id);
$stmt_favorites->execute();
$result_favorites = $stmt_favorites->get_result();
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

                <!-- Sekcja ulubionych budżetów -->
                <section class="widget favorite-budgets">
                    <h2>Ulubione Budżety</h2>
                    <?php if ($result_favorites->num_rows > 0): ?>
                        <ul class="budget-list">
                            <?php while ($row = $result_favorites->fetch_assoc()): ?>
                                <li class="budget-item">
                                    <article class="budget-summary">
                                        <strong><?= htmlspecialchars($row['budget_name']) ?></strong>
                                        <p>Limit: <?= htmlspecialchars($row['Amount_limit']) ?> zł</p>
                                        <p>Okres: <?= htmlspecialchars($row['Period_of_time']) ?></p>
                                        <p>Data rozpoczęcia: <?= htmlspecialchars($row['Start_date']) ?></p>
                                    </article>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>Nie masz jeszcze ulubionych budżetów.</p>
                    <?php endif; ?>
                </section>

                <!-- Slider -->
                <div class="slider">
                    <div class="slides">
                        <?php if (!empty($favorite_budgets)): ?>
                            <!-- Slajd 1 -->
                            <input type="radio" name="radio-btn" id="radio1">
                            <input type="radio" name="radio-btn" id="radio2">
                            <input type="radio" name="radio-btn" id="radio3">
                            <div class="slide first">
                                <div class="slide-content">
                                    <h1><?= htmlspecialchars($favorite_budgets[0]['budget_name']) ?></h1>
                                    <p>Limit: <?= htmlspecialchars($favorite_budgets[0]['Amount_limit']) ?> zł</p>
                                    <p>Okres: <?= htmlspecialchars($favorite_budgets[0]['Period_of_time']) ?></p>
                                    <p>Data rozpoczęcia: <?= htmlspecialchars($favorite_budgets[0]['Start_date']) ?></p>
                                </div>
                            </div>
                            <!-- Slajd 2 -->
                            <div class="slide">
                                <div class="slide-content">
                                    <?php if (isset($favorite_budgets[1])): ?>
                                        <h1><?= htmlspecialchars($favorite_budgets[1]['budget_name']) ?></h1>
                                        <p>Limit: <?= htmlspecialchars($favorite_budgets[1]['Amount_limit']) ?> zł</p>
                                        <p>Okres: <?= htmlspecialchars($favorite_budgets[1]['Period_of_time']) ?></p>
                                        <p>Data rozpoczęcia: <?= htmlspecialchars($favorite_budgets[1]['Start_date']) ?></p>
                                    <?php else: ?>
                                        <h1>Brak drugiego ulubionego budżetu</h1>
                                        <p>Dodaj więcej budżetów, aby je tutaj zobaczyć!</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Slajd 3 -->
                            <div class="slide">
                                <div class="slide-content">
                                    <?php if (isset($favorite_budgets[2])): ?>
                                        <h1><?= htmlspecialchars($favorite_budgets[2]['budget_name']) ?></h1>
                                        <p>Limit: <?= htmlspecialchars($favorite_budgets[2]['Amount_limit']) ?> zł</p>
                                        <p>Okres: <?= htmlspecialchars($favorite_budgets[2]['Period_of_time']) ?></p>
                                        <p>Data rozpoczęcia: <?= htmlspecialchars($favorite_budgets[2]['Start_date']) ?></p>
                                    <?php else: ?>
                                        <h1>Brak trzeciego ulubionego budżetu</h1>
                                        <p>Dodaj więcej budżetów, aby je tutaj zobaczyć!</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Domyślne slajdy -->
                            <input type="radio" name="radio-btn" id="radio1" checked>
                            <div class="slide first">
                                <div class="slide-content">
                                    <h1>Brak ulubionych budżetów</h1>
                                    <p>Dodaj swoje ulubione budżety, aby je tutaj zobaczyć!</p>
                                </div>
                            </div>
                            <input type="radio" name="radio-btn" id="radio2">
                            <div class="slide">
                                <div class="slide-content">
                                    <h1>Planowanie miesięczne</h1>
                                    <p>Przeglądaj miesięczne wydatki i oszczędności.</p>
                                </div>
                            </div>
                            <input type="radio" name="radio-btn" id="radio3">
                            <div class="slide">
                                <div class="slide-content">
                                    <h1>Planowanie tygodniowe</h1>
                                    <p>Analizuj krótkoterminowe wydatki i cele.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- Automatyczna nawigacja -->
                        <div class="navigation-auto">
                            <div class="auto-btn1"></div>
                            <div class="auto-btn2"></div>
                            <div class="auto-btn3"></div>
                        </div>
                    </div>
                    <!-- Manualna nawigacja -->
                    <div class="navigation-manual">
                        <label for="radio1" class="manual-btn"></label>
                        <label for="radio2" class="manual-btn"></label>
                        <label for="radio3" class="manual-btn"></label>
                    </div>
                </div>

                <!-- Slider -->
                <div class="slider">
                    <div class="slides">
                        <input type="radio" name="radio-btn" id="radio1">
                        <input type="radio" name="radio-btn" id="radio2">
                        <input type="radio" name="radio-btn" id="radio3">

                        <div class="slide first">
                            <img src="pictures/budzet-rok.png" alt="Budżet domowy" class="slide-image">
                            <div class="slide-content">
                                <h1>Wydatki roczne</h1>
                            </div>
                        </div>
                        <div class="slide">
                            <img src="pictures/budzet-miesiac.webp" alt="Planowanie oszczędności" class="slide-image">
                            <div class="slide-content">
                                <h1>Wydatki miesięczne</h1>
                            </div>
                        </div>
                        <div class="slide">
                            <!-- <img src="pictures/dream_chasing.png" alt="Oszczędzanie na przyszłość" class="slide-image"> -->
                            <div class="slide-content">
                                <h1>Wydatki tygodniowe</h1>
                            </div>
                        </div>
                        <div class="navigation-auto">
                            <div class="auto-btn1"></div>
                            <div class="auto-btn2"></div>
                            <div class="auto-btn3"></div>
                        </div>
                    </div>
                    <div class="navigation-manual">
                        <label for="radio1" class="manual-btn"></label>
                        <label for="radio2" class="manual-btn"></label>
                        <label for="radio3" class="manual-btn"></label>
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
