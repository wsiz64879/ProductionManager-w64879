<?php
session_start();

// Sprawdź, czy użytkownik jest zalogowany
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Połączenie z bazą danych
    $host = "localhost";
    $dbname = "w64879_projekt";
    $db_username = "root";
    $db_password = "";

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Pobierz typ uprawnień użytkownika z bazy danych
        $stmt = $db->prepare("SELECT Typ_uprawnienia FROM uprawnienia WHERE user_id = (SELECT user_id FROM users WHERE username = :username)");
        $stmt->execute(array(':username' => $username));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Sprawdź typ uprawnień użytkownika i przechowaj go w sesji
        if ($user && isset($user['Typ_uprawnienia'])) {
            $_SESSION["typ_uprawnienia"] = $user['Typ_uprawnienia'];
        } else {
            // Użytkownik nie ma przypisanego typu uprawnień
            $_SESSION["typ_uprawnienia"] = "Brak uprawnień";
        }

        // Przekieruj użytkownika do strony index.php
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        die("Błąd połączenia z bazą danych: " . $e->getMessage());
    }
} else {
    // Przekieruj użytkownika do strony logowania, jeśli nie jest zalogowany
    header("Location: login.php");
    exit();
}
?>
