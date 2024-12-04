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
    <link rel="stylesheet" href="../styles/pages/settings.scss">
    <link rel="icon" type="image/x-icon" href="pictures/logo.webp">
</head>
<body>
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
                    <img src="../pictures/user-photo.jpg" alt="User Photo" class="profile-pic">
                    <div class="user-profile-dropdown">
                        <div class="user-profile-dropdown-content">
                            <a href="settings.php">Ustawienia</a>
                            <a href="login_module/logout.php">Wyloguj</a>
                        </div>
                    </div>
                </button>
            </div>            
        </div>
    </header>
</body>
</html>