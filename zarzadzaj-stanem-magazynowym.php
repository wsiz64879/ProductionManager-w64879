<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css\zarzadzaj-zleceniami.css">
 
    <title>Zarządzanie stanem magazynowym</title>
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

    session_start();

    // Sprawdzenie, czy użytkownik jest zalogowany
    if (!isset($_SESSION['username'])) {
        // Przekierowanie do strony logowania
        header("Location: login.php");
        exit();
    }

// Funkcja do pobierania stanu magazynowego
function getStanMagazynowy() {
    global $db;
    $stmt = $db->query("SELECT * FROM stan_magazynowy");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Pobierz stan magazynowy
$stanMagazynowy = getStanMagazynowy();

// Przetwarzanie formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['przyjmij'])) {
        $nazwa = $_POST['nazwa'];
        $ilosc = $_POST['ilosc'];

        // Dodaj surowiec do stanu magazynowego
        $stmt = $db->prepare("INSERT INTO stan_magazynowy (nazwa, ilosc) VALUES (:nazwa, :ilosc)");
        $stmt->bindParam(':nazwa', $nazwa);
        $stmt->bindParam(':ilosc', $ilosc);
        $stmt->execute();

        // Przejdź na stronę zarządzania ponownie
        header("Location: zarzadzaj-stanem-magazynowym.php");
        exit();
    } elseif (isset($_POST['wydaj'])) {
        $id = $_POST['id'];
        $iloscWydawana = $_POST['ilosc_wydawana'];

        // Pobierz surowiec z magazynu
        $stmt = $db->prepare("SELECT * FROM stan_magazynowy WHERE id_stanu = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $surowiec = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($surowiec) {
            // Sprawdź, czy ilość surowca na magazynie jest większa lub równa żądanej ilości do wydania
            if ($surowiec['ilosc'] >= $iloscWydawana) {
                // Zmniejsz ilość surowca na magazynie
                $stmt = $db->prepare("UPDATE stan_magazynowy SET ilosc = ilosc - :ilosc WHERE id_stanu = :id");
                $stmt->bindParam(':ilosc', $iloscWydawana);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                // Przejdź na stronę zarządzania ponownie
                header("Location: zarzadzaj-stanem-magazynowym.php");
                exit();
            }
        }
    } elseif (isset($_POST['usun'])) {
        $id = $_POST['id'];

        // Pobierz surowiec z magazynu
        $stmt = $db->prepare("SELECT * FROM stan_magazynowy WHERE id_stanu = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $surowiec = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($surowiec) {
            // Wyświetl komunikat z pytaniem o potwierdzenie usunięcia
            echo '<script>
                    var confirmed = confirm("Czy na pewno chcesz usunąć surowiec: ' . $surowiec['nazwa'] . ' ?");
                    if (confirmed) {
                        // Potwierdzono usunięcie, wykonaj zapytanie
                        window.location.href = "usun-surowiec.php?id=" + ' . $surowiec['id_stanu'] . ';
                    } else {
                        // Anulowano usunięcie
                        window.location.href = "zarzadzaj-stanem-magazynowym.php";
                    }
                  </script>';
        }
    } elseif (isset($_POST['edytuj'])) {
        $id = $_POST['id'];
        $nazwa = $_POST['nazwa'];
        $ilosc = $_POST['ilosc'];

        // Zaktualizuj surowiec w magazynie
        $stmt = $db->prepare("UPDATE stan_magazynowy SET nazwa = :nazwa, ilosc = :ilosc WHERE id_stanu = :id");
        $stmt->bindParam(':nazwa', $nazwa);
        $stmt->bindParam(':ilosc', $ilosc);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Przejdź na stronę edytuj-stan-magazynowy.php
        header("Location: edytuj-stan-magazynowy.php?id=" . $id);
        exit();
    }
}

// Obsługa usuwania surowca
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Usuń surowiec z magazynu
    $stmt = $db->prepare("DELETE FROM stan_magazynowy WHERE id_stanu = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Przejdź na stronę zarządzania ponownie
    header("Location: zarzadzaj-stanem-magazynowym.php");
    exit();
}
?>
<p style="text-align: right; zindex: 1; margin-top: -10px;">Zalogowany użytkownik: <?php echo $_SESSION['username']; ?></p>
    <img style="zindex: -9; margin-top: -85px;" class="logo" src="img/logo.png" width="200px">
   <center><h2 style="margin-top: -65px;">Zarządzanie stanem magazynowym</h2></center>

<h3>Aktualny stan magazynowy:</h3>

<table>
    <tr>
        <th>Id stanu</th>
        <th>Nazwa surowca</th>
        <th>Ilość</th>
        <th>Akcje</th>
    </tr>
    <?php foreach ($stanMagazynowy as $surowiec) { ?>
        <tr>
            <td><?php echo $surowiec['id_stanu']; ?></td>
            <td><?php echo $surowiec['nazwa']; ?></td>
            <td><?php echo $surowiec['ilosc']; ?></td>
            <td>
                <form action="zarzadzaj-stanem-magazynowym.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $surowiec['id_stanu']; ?>">
                    <input type="submit" name="usun" value="Usuń" class="bzaloguj">
                </form>
                <form action="zarzadzaj-stanem-magazynowym.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $surowiec['id_stanu']; ?>">
                    <input type="hidden" name="nazwa" value="<?php echo $surowiec['nazwa']; ?>">
                    <input type="hidden" name="ilosc" value="<?php echo $surowiec['ilosc']; ?>">
                    <input type="submit" name="edytuj" value="Edytuj" class="bzaloguj">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>



    <table>
        <tr>
            <th>Przyjmowanie surowca:</th>
            <th>Wydawanie surowca:</th>
        </tr>
        <tr>
            <td><form action="zarzadzaj-stanem-magazynowym.php" method="POST">
    <label for="nazwa">Nazwa surowca:</label>
    <input type="text" name="nazwa" required><br>
    <label for="ilosc">Ilość:</label>
    <input style="margin-left: 76px; width: 170px;"  type="number" name="ilosc" required><br>
    <input type="submit" name="przyjmij" value="Przyjmij" class="bzaloguj">
</form></td>
            <td><form action="zarzadzaj-stanem-magazynowym.php" method="POST">
    <label for="id">Wybierz surowiec:</label>
    <select name="id" required>
        <?php foreach ($stanMagazynowy as $surowiec) { ?>
            <option value="<?php echo $surowiec['id_stanu']; ?>"><?php echo $surowiec['nazwa']; ?></option>
        <?php } ?>
    </select>
    <label for="ilosc_wydawana">Ilość:</label>
    <input type="number" name="ilosc_wydawana" required><br>
    <input type="submit" name="wydaj" value="Wydaj" class="bzaloguj">
</form>
</td>
        </tr>
		  </table><br><center>
		  <a href="index.php"><input type="submit" value="Wróć" class="bzaloguj"></a>
		</center>
</body>
</html>
