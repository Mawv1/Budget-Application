<?php
    session_start();
    session_unset();
    header('Location: ../landing_page.php');
?>