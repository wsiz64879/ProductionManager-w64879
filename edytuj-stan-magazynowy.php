<?php
// Połączenie z bazą danych
$db = new PDO('mysql:host=localhost;dbname=w64879_projekt', 'root', '');

// Pobierz ID surowca do edycji
$id = $_GET['id'];

// Pobierz dane surowca z bazy danych
$stmt = $db->prepare("SELECT * FROM stan_magazynowy WHERE id_stanu = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$surowiec = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$surowiec) {
    // Jeśli surowiec o podanym ID nie istnieje, przejdź do strony zarządzania
    header("Location: zarzadzaj-stanem-magazynowym.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazwa = $_POST['nazwa'];
    $ilosc = $_POST['ilosc'];

    // Zaktualizuj dane surowca w bazie danych
    $stmt = $db->prepare("UPDATE stan_magazynowy SET nazwa = :nazwa, ilosc = :ilosc WHERE id_stanu = :id");
    $stmt->bindParam(':nazwa', $nazwa);
    $stmt->bindParam(':ilosc', $ilosc);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Przejdź do strony zarządzania po zaktualizowaniu danych
    header("Location: zarzadzaj-stanem-magazynowym.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
 <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css\zarzadzaj-zleceniami.css">
 
    <title>Edytuj stan magazynowy</title>
</head>
<body>
<center>

<table style="width: 400px">

    <form action="edytuj-stan-magazynowy.php?id=<?php echo $id; ?>" method="POST">
   
  <tr>
    <th>Edytuj stan magazynowy</th>
    <th></th>
  </tr>
  <tr>
    <td> <label for="nazwa">Nazwa surowca:</label>
    </td>
    <td><input type="text" name="nazwa" value="<?php echo $surowiec['nazwa']; ?>" required><br>
        </td>
  </tr>
  <tr>
    <td><label for="ilosc">Ilość:</label>
        </td>
    <td><input type="number" name="ilosc" value="<?php echo $surowiec['ilosc']; ?>" required><br>
        </td>
  </tr>
</table>


        <input type="submit" value="Zapisz" class="bzaloguj">
    </form>

    <a href="zarzadzaj-stanem-magazynowym.php"><input type="button" value="Anuluj" class="bzaloguj"></a>
</center>
</body>
</html>
