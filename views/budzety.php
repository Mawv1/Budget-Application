<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje Budżety</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="budgets_utils/budgets_style.sass">
    <link rel="icon" type="image/x-icon" href="pictures/logo.webp">
</head>
<body>
    <header class="header">
        <a href="../index.php" class="app-name">Powrót do strony głównej</a>
        <h1>Twoje Budżety</h1>
    </header>
    <div class="content">
        <!-- Dodaj treść dla widoku Twoje Budżety -->
        <p>Witaj w sekcji Twoje Budżety!</p>
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
