<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');
    exit();
}

require_once '../connect.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Pobranie wszystkich raportów
$sql_reports = "SELECT r.report_id, 
                       u1.Name AS reporting_name, 
                       u1.Surname AS reporting_surname, 
                       u2.Name AS reported_name, 
                       u2.Surname AS reported_surname, 
                       r.reported_section, 
                       r.report_description, 
                       r.report_date,
                       r.reported_user
                FROM reports r
                JOIN users u1 ON r.User_id = u1.User_id
                LEFT JOIN users u2 ON r.reported_user = u2.User_id";
$result_reports = $conn->query($sql_reports);
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/admin_panel_styles/admin_panel_style.css">
</head>
<body>
    <header class="header">
        <h1>Panel Administratora</h1>
    </header>
    <main>
        <h2>Lista zgłoszeń</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Użytkownik zgłaszający</th>
                    <th>Użytkownik zgłoszony</th>
                    <th>Sekcja</th>
                    <th>Opis zgłoszenia</th>
                    <th>Data zgłoszenia</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_reports->num_rows > 0): ?>
                    <?php while ($report = $result_reports->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($report['report_id']) ?></td>
                            <td><?= htmlspecialchars($report['reporting_name'] . ' ' . $report['reporting_surname']) ?></td>
                            <td>
                                <?php if ($report['reported_name']): ?>
                                    <?= htmlspecialchars($report['reported_name'] . ' ' . $report['reported_surname']) ?>
                                <?php else: ?>
                                    <i>Nieznany użytkownik</i>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($report['reported_section']) ?></td>
                            <td><?= htmlspecialchars($report['report_description']) ?></td>
                            <td><?= htmlspecialchars($report['report_date']) ?></td>
                            <td>
                                <?php if ($report['reported_user']): ?>
                                    <form action="admin_panel_utils/block_user.php" method="post">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($report['reported_user']) ?>">
                                        <input type="hidden" name="admin_id" value="<?= $_SESSION['id'] ?>">
                                        <input type="text" name="block_reason" placeholder="Powód blokady" required>
                                        <button type="submit">Zablokuj</button>
                                    </form>
                                <?php else: ?>
                                    <i>Brak akcji</i>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Brak zgłoszeń</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
