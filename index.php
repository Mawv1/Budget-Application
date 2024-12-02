<?php
    session_start();

    if(!isset($_SESSION['logged'])){
        // użytkownik nie jest zalogowany
        header('Location: login_module/login.php');
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
                <!-- <span>Witaj, xyz!</span> -->
            </div>"
            <div class="user-profile">
                <button class="profile-button">
                    <img src="pictures/user-photo.jpg" alt="User Photo" class="profile-pic">
                    <div class="user-profile-dropdown">
                        <div class="user-profile-dropdown-content">
                            <a href="#">Ustawienia</a>
                            <?php
                                echo "<a href='login_module/logout.php'>Wyloguj</a>";
                            ?>
                            <!-- <a href="landing_page.php">Wyloguj</a>1 -->
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
        
                <!-- Slider -->
                <!-- <div class="slider-container">
                    <div class="slider">
                        <div class="slider-item">
                            <img src="pictures/slider-image1.jpg" alt="Item 1">
                            <span>Opis 1</span>
                        </div>
                        <div class="slider-item">
                            <img src="pictures/slider-image2.jpg" alt="Item 2">
                            <span>Opis 2</span>
                        </div>
                        <div class="slider-item">
                            <img src="pictures/slider-image3.jpg" alt="Item 3">
                            <span>Opis 3</span>
                        </div>
                    </div>
                    <div class="slider-nav">
                        <button class="prev">&lt;</button>
                        <button class="next">&gt;</button>
                    </div>
                </div>                 -->
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
                                <h1>Zarządzaj swoim budżetem z łatwością</h1>
                                <p>Kontrola wydatków nigdy nie była prostsza.</p>
                                <a href="login_module/register.php" class="cta-button">Rozpocznij za darmo</a>
                            </div>
                        </div>
                        <div class="slide">
                            <img src="pictures/savings.png" alt="Planowanie oszczędności" class="slide-image">
                            <div class="slide-content">
                                <h1>Planuj oszczędności z nami</h1>
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
            </div>
        </main>
    </div>
    <!-- Stopka strony -->
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
