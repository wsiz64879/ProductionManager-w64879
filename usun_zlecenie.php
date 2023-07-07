<?php
session_start();

// Połączenie z bazą danych
$host = "localhost";
$dbname = "w64879_projekt";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

// Pobierz id zlecenia z parametru GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Usuń zlecenie z bazy danych
    $stmt = $db->prepare("DELETE FROM zlecenia WHERE Id_zlecenia = :id");
    $stmt->execute(array(':id' => $id));

    // Przekieruj na stronę zarządzania zleceniami
    header("Location: zarzadzaj-zleceniami.php");
    exit();
} else {
    // Jeśli brakuje parametru id, przekieruj na stronę zarządzania zleceniami
    header("Location: zarzadzaj-zleceniami.php");
    exit();
}
?>
