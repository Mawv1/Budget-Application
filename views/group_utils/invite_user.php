<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../login_module/login.php');
    exit();
}

require_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = intval($_POST['group_id']);
    $user_email = trim($_POST['user_email']);

    if (empty($user_email)) {
        $_SESSION['error'] = "Email użytkownika nie może być pusty.";
        header('Location: ../nowa_grupa.php');
        exit();
    }

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Sprawdź, czy użytkownik istnieje
    $sql_user = "SELECT id FROM users WHERE email = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $user_email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows === 0) {
        $_SESSION['error'] = "Nie znaleziono użytkownika z podanym adresem email.";
        header('Location: ../nowa_grupa.php');
        exit();
    }

    $user = $result_user->fetch_assoc();
    $user_id = $user['id'];

    // Dodaj użytkownika do grupy
    $sql_invite = "INSERT INTO group_members (group_id, user_id) VALUES (?, ?)";
    $stmt_invite = $conn->prepare($sql_invite);
    $stmt_invite->bind_param("ii", $group_id, $user_id);

    if ($stmt_invite->execute()) {
        $_SESSION['success'] = "Użytkownik został pomyślnie zaproszony do grupy.";
    } else {
        $_SESSION['error'] = "Wystąpił błąd podczas zapraszania użytkownika.";
    }

    $stmt_user->close();
    $stmt_invite->close();
    $conn->close();

    header('Location: ../nowa_grupa.php');
    exit();
}
?>
