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

$user_id = $_SESSION['id'];
$error = '';
$success = '';

// Pobierz listę użytkowników
$sql_users = "SELECT User_id, CONCAT(name, ' ', surname, ' (', email, ')') AS user_display FROM users WHERE User_id != ?";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("i", $user_id);
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$users = [];
while ($row = $result_users->fetch_assoc()) {
    $users[] = [
        'id' => $row['User_id'],
        'user_display' => htmlspecialchars($row['user_display'], ENT_QUOTES, 'UTF-8'),
    ];
}
$stmt_users->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_type = $_POST['report_type'];
    $report_description = trim($_POST['report_description']);
    $reported_user = null;
    $reported_section = null;

    if ($report_type === 'user') {
        $reported_user = intval($_POST['reported_user']);
    } elseif ($report_type === 'section') {
        $reported_section = trim($_POST['reported_section']);
    }

    if (empty($report_description) || ($report_type === 'user' && empty($reported_user)) || ($report_type === 'section' && empty($reported_section))) {
        $error = "Wypełnij wszystkie wymagane pola.";
    } else {
        $sql = "INSERT INTO reports (user_id, reported_section, reported_user, report_description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isis", $user_id, $reported_section, $reported_user, $report_description);

        if ($stmt->execute()) {
            $success = "Zgłoszenie zostało przesłane.";
        } else {
            $error = "Wystąpił błąd podczas przesyłania zgłoszenia.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <link rel="stylesheet" href="../styles/contact_styles/contact_style.css">
    <link rel="stylesheet" href="../styles/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportType = document.getElementById('report_type');
            const userField = document.getElementById('user_field');
            const sectionField = document.getElementById('section_field');

            reportType.addEventListener('change', function () {
                if (this.value === 'user') {
                    userField.style.display = 'block';
                    sectionField.style.display = 'none';
                } else if (this.value === 'section') {
                    userField.style.display = 'none';
                    sectionField.style.display = 'block';
                }
            });
        });
    </script>
</head>
<body>
    <header class="header">
        <h1>Kontakt z nami</h1>
    </header>
    <div class="wrapper">
        <main class="content">
            <section class="contact-form">
                <h2>Formularz zgłoszeniowy</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST" action="contact.php">
                    <label for="report_type">Wybierz typ zgłoszenia:</label>
                    <select id="report_type" name="report_type" required>
                        <option value="">-- Wybierz --</option>
                        <option value="user">Zgłoś użytkownika</option>
                        <option value="section">Zgłoś sekcję</option>
                    </select>

                    <!-- Pole wyboru użytkownika -->
                    <div id="user_field" style="display: none;">
                        <label for="reported_user">Wybierz użytkownika:</label>
                        <input type="text" id="user_search" placeholder="Wpisz imię, nazwisko lub email" oninput="filterUsers()">
                        <select id="reported_user" name="reported_user">
                            <option value="">-- Wybierz użytkownika --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['user_display']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Pole zgłoszenia sekcji -->
                    <div id="section_field" style="display: none;">
                        <label for="reported_section">Wpisz nazwę sekcji:</label>
                        <input type="text" id="reported_section" name="reported_section" placeholder="Nazwa sekcji...">
                    </div>

                    <label for="report_description">Opis problemu:</label>
                    <textarea id="report_description" name="report_description" placeholder="Opisz szczegóły..." rows="5" required></textarea>

                    <button type="submit">Wyślij zgłoszenie</button>
                </form>
            </section>

            <section class="contact-info">
                <h2>Informacje kontaktowe</h2>
                <p>Email: <a href="mailto:support@budapp.pl" class="email-link">support@budapp.pl</a></p>
                <p>Telefon: <a href="tel:+48123456789">+48 123 456 789</a></p>
                <p>Adres: ul. Przykładowa 123, 00-001 Warszawa</p>
            </section>
        </main>
    </div>
    <footer class="footer">
        <p>&copy; <?= date("Y") ?> BudApp. Wszelkie prawa zastrzeżone.</p>
    </footer>

    <script>
        function filterUsers() {
            const input = document.getElementById('user_search').value.toLowerCase();
            const options = document.querySelectorAll('#reported_user option');

            options.forEach(option => {
                if (option.text.toLowerCase().includes(input) || option.value.includes(input)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
