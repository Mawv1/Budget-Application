<?php
    session_start();

    if(!isset($_SESSION['logged'])){
        // użytkownik nie jest zalogowany
        header('Location: ../login_module/login.php');
        exit();
    }
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
                    <img src="pictures/user-photo.jpg" alt="User Photo" class="profile-pic">
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

                <!-- Slider -->
                <div class="slider">
                    <div class="slides">
                        <input type="radio" name="radio-btn" id="radio1">
                        <input type="radio" name="radio-btn" id="radio2">
                        <input type="radio" name="radio-btn" id="radio3">

                        <div class="slide first">
                            <img src="pictures/budzet-rok.png" alt="Budżet domowy" class="slide-image">
                            <div class="slide-content">
                                <h1>Budzet roczny</h1>
                            </div>
                        </div>
                        <div class="slide">
                            <img src="pictures/budzet-miesiac.webp" alt="Planowanie oszczędności" class="slide-image">
                            <div class="slide-content">
                                <h1>Budzet miesięczny</h1>
                            </div>
                        </div>
                        <div class="slide">
                            <!-- <img src="pictures/dream_chasing.png" alt="Oszczędzanie na przyszłość" class="slide-image"> -->
                            <div class="slide-content">
                                <h1>Budzet tygodniowy</h1>
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
    <footer class="footer">
        <p>&copy; 2024 BudApp. Wszelkie prawa zastrzeżone.</p>
        <ul class="footer-links">
            <li><a href="#">Polityka prywatności</a></li>
            <li><a href="#">Regulamin</a></li>
            <li><a href="#">Kontakt</a></li>
        </ul>
    </footer>
</body>
</html>
