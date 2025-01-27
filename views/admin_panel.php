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
                       r.reported_user,
                       r.is_done,
                       (SELECT COUNT(*) FROM user_blocks WHERE user_id = r.reported_user) AS is_blocked
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
        <div class="comeback">
            <div class="logo-container">
                <button onclick="window.location.href='../index.php'" class="logo-button">
                    <img src="../pictures/logo.webp" alt="Logo" class="logo">
                    <span class="app-name">BudApp</span>
                </button>
            </div>
            <h1 class="admin-panel-title">Panel Administratora</h1>
        </div>
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
                    <th>Zrobione?</th>
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
                                    <?php if ($report['is_blocked'] > 0): ?>
                                        <form action="admin_panel_utils/unblock_user.php" method="post">
                                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($report['reported_user']) ?>">
                                            <input type="hidden" name="admin_id" value="<?= $_SESSION['id'] ?>">
                                            <button type="submit" class="unblock-button">Odblokuj</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="admin_panel_utils/block_user.php" method="post">
                                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($report['reported_user']) ?>">
                                            <input type="hidden" name="admin_id" value="<?= $_SESSION['id'] ?>">
                                            <input type="text" name="block_reason" placeholder="Powód blokady" required>
                                            <button type="submit" class="block-button">Zablokuj</button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i>Brak akcji</i>
                                <?php endif; ?>

                                <!-- Przycisk usuwający zgłoszenie -->
                                <form action="admin_panel_utils/delete_report.php" method="post">
                                    <input type="hidden" name="report_id" value="<?= htmlspecialchars($report['report_id']) ?>">
                                    <button type="submit" class="delete-button">Usuń zgłoszenie</button>
                                </form>

                                <!-- Przycisk odznaczający zgłoszenie jako zrobione -->
                                <td>
                                    <form action="admin_panel_utils/update_done_status.php" method="post">
                                        <input type="hidden" name="report_id" value="<?= htmlspecialchars($report['report_id']) ?>">
                                        <input type="checkbox" name="is_done" value="1" <?= $report['is_done'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                    </form>
                                </td>
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
