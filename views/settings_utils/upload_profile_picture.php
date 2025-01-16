<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../../login_module/login.php');
    exit();
}

header('Content-Type: application/json');

$uploadDir = '../../pictures/uploads/';
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
$maxFileSize = 6 * 1024 * 1024; // 6 MB

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => "Błąd przesyłania pliku."]);
        exit();
    }

    if ($file['size'] > $maxFileSize) {
        echo json_encode(["success" => false, "message" => "Plik jest zbyt duży. Maksymalny rozmiar to 6 MB."]);
        exit();
    }

    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedExtensions)) {
        echo json_encode(["success" => false, "message" => "Niedozwolony format pliku. Dozwolone: jpg, jpeg, png, webp."]);
        exit();
    }

    $fileName = uniqid('profile_', true) . '.' . $fileExt;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "demo";

        $conn = new mysqli($host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą danych."]);
            exit();
        }

        $userId = $_SESSION['id'];
        $sql = "UPDATE users SET Profile_picture = ? WHERE User_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $fileName, $userId);

        if ($stmt->execute()) {
            $_SESSION['profile_picture'] = $fileName;
            echo json_encode(["success" => true, "message" => "Zdjęcie profilowe zostało zaktualizowane!", "fileName" => $fileName]);
        } else {
            echo json_encode(["success" => false, "message" => "Błąd podczas aktualizacji w bazie danych."]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["success" => false, "message" => "Nie udało się zapisać pliku na serwerze."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe żądanie."]);
}
?>
