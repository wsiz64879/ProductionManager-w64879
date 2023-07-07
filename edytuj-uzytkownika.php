<?php
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

// Pobierz ID użytkownika do edycji
$id = $_GET['id'];

// Pobierz dane użytkownika z bazy danych
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$uzytkownik = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$uzytkownik) {
    // Jeśli użytkownik o podanym ID nie istnieje, przejdź do strony zarządzania
    header("Location: zarzadzaj-pracownikami.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Zaktualizuj dane użytkownika w bazie danych
    $stmt = $db->prepare("UPDATE users SET username = :username, password = :password WHERE user_id = :id");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Przejdź na stronę zarządzania pracownikami ponownie
    header("Location: zarzadzaj-pracownikami.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css\zarzadzaj-zleceniami.css">
 
    <title>Edytuj użytkownika</title>
</head>
<body>
    <img style="zindex: -9; margin-top:-55px;" class="logo" src="img/logo.png" width="200px">
    <center><h2 style="margin-top: -70px;">Edytuj użytkownika</h2></center>
<br><br><center>

<table style="width: 500px;">
  <tr>
  <form action="edytuj-uzytkownika.php?id=<?php echo $id; ?>" method="POST">

    <th>Edytuj Użytkownika</th>
    <th></th>
  </tr>
  <tr>
    <td><label for="username">Nazwa użytkownika:</label>
    </td>
    <td><input type="text" name="username" value="<?php echo $uzytkownik['username']; ?>" required><br>
    </td>
  </tr>
  <tr>
    <td>    <label for="password">Hasło:</label>
    </td>
    <td><input type="password" name="password" value="<?php echo $uzytkownik['password']; ?>" required><br>
 </td>
  </tr>
</table>



<br>

   <input class="bzaloguj" type="submit" value="Zapisz zmiany">
</form>
<a href="zarzadzaj-pracownikami.php"><input class="bzaloguj" type="submit" value="Anuluj"></a>
</body></center>
</html>
