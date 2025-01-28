<?php
session_start();

if (!isset($_POST["email"]) || !isset($_POST["password"])) {
    // Przekierowanie do strony logowania, jeśli brak danych logowania
    header('Location: login.php');
    exit();
}

require_once "../connect.php";

try {
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0) {
        throw new Exception($connection->connect_error);
    } else {
        // Pobranie danych z formularza
        $login = trim($_POST['email']); // Usunięcie zbędnych spacji
        $password = trim($_POST['password']);

        // Użycie przygotowanych zapytań (Prepared Statements) dla bezpieczeństwa
        $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception($connection->error);
        }

        // Bindowanie parametrów i wykonanie zapytania
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        // Sprawdzenie liczby wierszy w wyniku
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Weryfikacja hasła
            if (password_verify($password, $row['Password'])) {
                // Logowanie powiodło się
                $_SESSION['logged'] = true;

                // Zapisanie danych użytkownika w sesji
                $_SESSION['id'] = $row['User_id'];
                $_SESSION['name'] = htmlspecialchars($row['Name'], ENT_QUOTES, "UTF-8");
                $_SESSION['email'] = htmlspecialchars($row['Email'], ENT_QUOTES, "UTF-8");
                $_SESSION['profile_picture'] = htmlspecialchars($row['profile_picture'], ENT_QUOTES, "UTF-8");

                $guestFavorites = $_SESSION['favorites'] ?? [];
                if (!empty($guestFavorites)) {
                    error_log("Zawartość ulubionych budżetów w sesji: " . print_r($guestFavorites, true));
                
                    // Przygotowanie zapytań do `budgets` i `favorite_budgets`
                    $stmtInsertBudget = $connection->prepare("INSERT INTO budgets (budget_name, User_id, Amount_limit, Period_of_time, Start_date) VALUES (?, ?, ?, ?, ?)");
                    $stmtInsertFavorite = $connection->prepare("INSERT INTO favorite_budgets (user_id, budget_id) VALUES (?, ?)");
                
                    if (!$stmtInsertBudget || !$stmtInsertFavorite) {
                        error_log("Błąd przygotowania zapytań: " . $connection->error);
                        throw new Exception("Błąd przygotowania zapytań.");
                    }
                
                    foreach ($guestFavorites as $favorite) {
                        // Pobranie danych z sesji
                        $budgetName = $favorite['budget_name'] ?? 'Domyślny budżet';
                        $amountLimit = $favorite['Amount_limit'] ?? 0.00;
                        $periodOfTime = $favorite['Period_of_time'] ?? 'Nieokreślony';
                        $startDate = date("Y-m-d H:i:s"); // Data logowania
                        $userId = $row['User_id']; // ID zalogowanego użytkownika
                
                        // Wstaw dane do tabeli `budgets`
                        $stmtInsertBudget->bind_param("sidss", $budgetName, $userId, $amountLimit, $periodOfTime, $startDate);
                        if ($stmtInsertBudget->execute()) {
                            $newBudgetId = $stmtInsertBudget->insert_id; // ID nowego budżetu
                            error_log("Dodano budżet: $budgetName z ID: $newBudgetId");
                
                            // Wstaw dane do tabeli `favorite_budgets`
                            $stmtInsertFavorite->bind_param("ii", $userId, $newBudgetId);
                            if ($stmtInsertFavorite->execute()) {
                                error_log("Dodano do ulubionych budżet ID: $newBudgetId");
                            } else {
                                error_log("Błąd dodawania do ulubionych: " . $stmtInsertFavorite->error);
                                throw new Exception("Błąd dodawania do ulubionych.");
                            }
                        } else {
                            error_log("Błąd dodawania budżetu: " . $stmtInsertBudget->error);
                            throw new Exception("Błąd dodawania budżetu.");
                        }
                    }
                
                    $stmtInsertBudget->close();
                    $stmtInsertFavorite->close();
                
                    // Wyczyść ulubione budżety w sesji po przeniesieniu do bazy
                    $_SESSION['favorites'] = [];
                }
                
                unset($_SESSION['error']); // Usunięcie błędów z sesji
                $result->free_result();
                $stmt->close();
                header('Location: ../index.php');
                exit();
            } else {
                // Dobry login, złe hasło
                $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                $stmt->close();
                header('Location: login.php');
                exit();
            }
        } else {
            // Zły login
            $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
            $stmt->close();
            header('Location: login.php');
            exit();
        }
    }
} catch (Exception $e) {
    echo '<span style="color:red">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
    // W trybie produkcyjnym NIE wyświetlaj szczegółowych błędów
    // Możesz logować szczegóły błędu w pliku logów
    error_log($e->getMessage());
     // Logowanie szczegółowych informacji o błędzie
    echo '<span style="color:red;">Błąd: ' . $e->getMessage() . '</span>';
}
?>
