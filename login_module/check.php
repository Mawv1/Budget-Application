<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoja Aplikacja Budżetowa</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo.webp">
    <script src="slider.js"></script>
    <script src="logout.js"></script>
</head>
<body>
    <?php
        echo "<span>Witaj ".$S_SESSION['name']."!</span>";
    ?>
</body>
</html>
