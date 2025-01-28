<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['logged'])) {
    header('Location: login_module/login.php');
    exit();
}

require_once 'connect.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Pobranie szczegółów blokady użytkownika
$user_id = $_SESSION['id'];
$sql_block = "SELECT b.block_reason, b.block_date, u.Name AS admin_name, u.Surname AS admin_surname
              FROM user_blocks b
              JOIN users u ON b.admin_id = u.User_id
              WHERE b.user_id = ?";
$stmt_block = $conn->prepare($sql_block);
$stmt_block->bind_param("i", $user_id);
$stmt_block->execute();
$result_block = $stmt_block->get_result();

if ($result_block->num_rows > 0) {
    $block_info = $result_block->fetch_assoc();
} else {
    // Jeśli użytkownik nie jest zablokowany, przekieruj go na stronę główną
    header('Location: index.php');
    exit();
}

$stmt_block->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konto zablokowane</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/blocked_notifications/blocked_notification.css">
</head>
<body>
    <div class="notification-container">
        <h1>Konto zablokowane</h1>
        <p>Twoje konto zostało zablokowane przez administratora.</p>
        <p><strong>Powód blokady:</strong> <?= htmlspecialchars($block_info['block_reason']) ?></p>
        <p><strong>Data blokady:</strong> <?= htmlspecialchars($block_info['block_date']) ?></p>
        <p><strong>Zablokowane przez:</strong> <?= htmlspecialchars($block_info['admin_name'] . ' ' . $block_info['admin_surname']) ?></p>
        <p>Jeśli uważasz, że doszło do pomyłki, skontaktuj się z administratorem.</p>
        <a href="views/contact.php" class="contact-link">Skontaktuj się z administratorem</a>
    </div>
</body>
</html>
