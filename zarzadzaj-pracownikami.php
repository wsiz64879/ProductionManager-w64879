<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    // Przekierowanie do strony logowania
    header("Location: login.php");
    exit();
}

// Sprawdzenie uprawnień użytkownika
if ($_SESSION['typ_uprawnienia'] !== "Administrator") {
    // Przekierowanie do strony braku uprawnień
    header("Location: brak-uprawnien.php");
    exit();
}

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

// Funkcja do pobierania listy pracowników wraz z typami uprawnień
function getPracownicy()
{
    global $db;
    $stmt = $db->query("SELECT u.user_id, u.username, u.password, p.Typ_uprawnienia FROM users u LEFT JOIN uprawnienia p ON u.user_id = p.user_id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Pobierz listę pracowników wraz z typami uprawnień
$pracownicy = getPracownicy();

// Przetwarzanie formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dodaj'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $typUprawnienia = $_POST['typ_uprawnienia'];

        // Dodaj użytkownika
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $userId = $db->lastInsertId();

        // Dodaj typ uprawnienia użytkownika
        $stmt = $db->prepare("INSERT INTO uprawnienia (user_id, Typ_uprawnienia) VALUES (:user_id, :typ_uprawnienia)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':typ_uprawnienia', $typUprawnienia);
        $stmt->execute();

        // Przejdź na stronę zarządzania pracownikami ponownie
        header("Location: zarzadzaj-pracownikami.php");
        exit();
    } elseif (isset($_POST['usun'])) {
        $id = $_POST['id'];

        // Usuń użytkownika
        $stmt = $db->prepare("DELETE FROM users WHERE user_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Usuń typ uprawnienia użytkownika
        $stmt = $db->prepare("DELETE FROM uprawnienia WHERE user_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Przejdź na stronę zarządzania pracownikami ponownie
        header("Location: zarzadzaj-pracownikami.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css\zarzadzaj-zleceniami.css">
 
    <title>Zarządzanie pracownikami</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
   <p style="text-align: right; zindex: 1; margin-top: -10px;">Zalogowany użytkownik: <?php echo $_SESSION['username']; ?></p>
    <img style="zindex: -9; margin-top: -85px;" class="logo" src="img/logo.png" width="200px">
    <center><h2 style="margin-top: -70px;">Zarządzanie pracownikami</h2></center>

<h3>Aktualna lista pracowników:</h3>

<table>
    <tr>
        <th>Id pracownika</th>
        <th>Nazwa użytkownika</th>
        <th>Hasło</th>
        <th>Typ uprawnienia</th>
        <th>Akcje</th>
    </tr>
    <?php foreach ($pracownicy as $pracownik) { ?>
        <tr>
            <td><?php echo $pracownik['user_id']; ?></td>
            <td><?php echo $pracownik['username']; ?></td>
            <td><?php echo $pracownik['password']; ?></td>
            <td><?php echo $pracownik['Typ_uprawnienia']; ?></td>
            <td>
                <form action="zarzadzaj-pracownikami.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $pracownik['user_id']; ?>">
                    <input class="bzaloguj" type="submit" name="usun" value="Usuń">
                </form>
                <form action="edytuj-uzytkownika.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo $pracownik['user_id']; ?>">
                    <input class="bzaloguj" type="submit" name="edytuj" value="Edytuj">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<center><br>

<table style="width:500px;">
  <tr>
  <form action="zarzadzaj-pracownikami.php" method="POST">
    <th>Dodawanie pracownika:</th>
    <th></th>
  </tr>
  <tr>
    <td>  <label for="username">Nazwa użytkownika:</label></td>
    <td>    <input type="text" name="username" required></td>
  </tr>
  <tr>
    <td> <label for="password">Hasło:</label></td>
    <td>    <input type="password" name="password" required></td>
  </tr>
  <tr>
    <td><label for="typ_uprawnienia">Typ uprawnienia:</label></td>
    <td><select name="typ_uprawnienia" required>
        <option value="Administrator">Administrator</option>
        <option value="Pracownik">Pracownik</option>
    </select></td>
  </tr>
</table><br>
    <input type="submit" name="dodaj" class="bzaloguj" value="Dodaj">
</form>

<a href="index.php"><input class="bzaloguj" type="submit" value="Wróć"></a>
</center>

</body>
</html>
