<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../login_module/login.php');
    exit();
}

require_once '../connect.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$user_id = $_SESSION['id']; // ID zalogowanego użytkownika

// Pobranie grup użytkownika
$sql_groups = "SELECT * FROM groups WHERE created_by = ?";
$stmt_groups = $conn->prepare($sql_groups);
$stmt_groups->bind_param("i", $user_id);
$stmt_groups->execute();
$result_groups = $stmt_groups->get_result();
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stwórz Nową Grupę</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/groups_styles/new_group.css">
</head>
<body>
    <header class="header">
        <a href="../index.php" class="app-name">Powrót do strony głównej</a>
        <h1>Stwórz Nową Grupę</h1>
    </header>
    <div class="content">
        <!-- Sekcja tworzenia grupy -->
        <section class="create-group">
            <h2>Stwórz Nową Grupę</h2>
            <form action="group_utils/create_group.php" method="post">
                <label for="group_name">Nazwa grupy:</label>
                <input type="text" id="group_name" name="group_name" placeholder="Wpisz nazwę grupy" required>
                
                <label for="group_budget">Budżet grupy:</label>
                <input type="number" id="group_budget" name="group_budget" placeholder="Wpisz limit budżetu" required>
                
                <button type="submit">Stwórz Grupę</button>
            </form>
        </section>

        <!-- Sekcja zarządzania grupami -->
        <section class="manage-groups">
            <h2>Twoje Grupy</h2>
            <?php if ($result_groups->num_rows > 0): ?>
                <ul class="group-list">
                    <?php while ($group = $result_groups->fetch_assoc()): ?>
                        <li class="group-item">
                            <strong><?= htmlspecialchars($group['group_name']) ?></strong>
                            <p>Budżet: <?= htmlspecialchars($group['budget_limit']) ?> zł</p>
                            <a href="group_utils/manage_group.php?group_id=<?= $group['group_id'] ?>" class="button">Zarządzaj</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nie należysz jeszcze do żadnej grupy.</p>
            <?php endif; ?>
        </section>

        <!-- Sekcja zapraszania użytkowników -->
        <section class="invite-users">
            <h2>Zaproś Użytkownika</h2>
            <?php if ($result_groups->num_rows > 0): ?>
                <form action="group_utils/invite_user.php" method="post">
                    <label for="group_id">Wybierz grupę:</label>
                    <select id="group_id" name="group_id" required>
                        <?php
                        $result_groups->data_seek(0); // Resetuje wskaźnik wyników
                        while ($group = $result_groups->fetch_assoc()): ?>
                            <option value="<?= $group['group_id'] ?>"><?= htmlspecialchars($group['group_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    
                    <label for="user_email">Email użytkownika:</label>
                    <input type="email" id="user_email" name="user_email" placeholder="Wpisz email użytkownika" required>
                    
                    <button type="submit">Zaproś</button>
                </form>
            <?php else: ?>
                <p>Najpierw stwórz grupę, aby móc zapraszać użytkowników.</p>
            <?php endif; ?>
        </section>
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

<?php
$stmt_groups->close();
$conn->close();
?>
