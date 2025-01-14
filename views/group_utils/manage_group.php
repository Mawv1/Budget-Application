<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../login_module/login.php');
    exit();
}

require_once '../../connect.php';

$group_id = intval($_GET['group_id']);

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$sql = "SELECT * FROM groups WHERE group_id = ? AND created_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $group_id, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Nie znaleziono grupy lub nie masz uprawnień do jej zarządzania.";
    exit();
}

$group = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzaj grupą</title>
    <link rel="stylesheet" href="../../styles/style.css">
    <link rel="stylesheet" href="../../styles/groups_styles/manage_group.css">
</head>
<body>
    <header class="header">
        <a href="../nowa_grupa.php" class="app-name">Powrót do strony głównej</a>
        <h1>Zarządzaj grupą: <?= htmlspecialchars($group['group_name']) ?></h1>
    </header>
    <div class="content">
        <p>Budżet grupy: <?= htmlspecialchars($group['budget_limit']) ?> zł</p>
        <!-- Możliwość edycji lub usunięcia grupy -->
        <form action="delete_group.php" method="post" onsubmit="return confirm('Czy na pewno chcesz usunąć grupę?')">
            <input type="hidden" name="group_id" value="<?= $group_id ?>">
            <button type="submit">Usuń grupę</button>
        </form>
    </div>
</body>
</html>
