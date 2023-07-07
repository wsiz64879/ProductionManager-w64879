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

// Sprawdź, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $nazwa = $_POST["nazwa"];
    $ilosc = $_POST["ilosc"];
    $zleceniodawca = $_POST["zleceniodawca"];
    $data = $_POST["date"];

    // Dodaj zlecenie do bazy danych
    $stmt = $db->prepare("INSERT INTO zlecenia (nazwa_zlecenia, ilosc, zleceniodawca, data_dodania_zlecenia, data_zrealizowania_zlecenia) VALUES (:nazwa, :ilosc, :zleceniodawca, NOW(), :data)");
    $stmt->execute(array(':nazwa' => $nazwa, ':ilosc' => $ilosc, ':zleceniodawca' => $zleceniodawca, ':data' => $data));

    // Przekieruj na stronę zarządzania zleceniami
    header("Location: zarzadzaj-zleceniami.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj zlecenie</title>
</head>
<body>
    <h2>Dodaj zlecenie</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="date" value="<?php echo $_POST['date']; ?>">
        <label for="nazwa">Nazwa zlecenia:</label>
        <input type="text" name="nazwa" required><br>
        <label for="ilosc">Ilość:</label>
        <input type="text" name="ilosc" required><br>
        <label for="zleceniodawca">Zleceniodawca:</label>
        <input type="text" name="zleceniodawca" required><br>
        <input type="submit" value="Dodaj zlecenie">
    </form>
</body>
</html>