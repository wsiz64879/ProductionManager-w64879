<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css\zarzadzaj-zleceniami.css">
    <title>Zarządzanie zleceniami</title>
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

        .selected {
            background-color: #e6e6e6;
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

    // Funkcja do pobierania zleceń na dany dzień
    function getZlecenia($date)
    {
        global $db;
        $stmt = $db->prepare("SELECT zlecenia.*, stan_zlecenia.ilosc AS wykonane, stan_zlecenia.ostatnia_zmiana FROM zlecenia LEFT JOIN stan_zlecenia ON zlecenia.Id_zlecenia = stan_zlecenia.Id_zlecenia WHERE zlecenia.data_zrealizowania_zlecenia = :date");
        $stmt->execute(array(':date' => $date));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funkcja do formatowania daty
    function formatDate($date)
    {
        return date("Y-m-d", strtotime($date));
    }

    // Sprawdź, czy wybrano datę
    if (isset($_GET['date'])) {
        $selectedDate = $_GET['date'];
    } else {
        $selectedDate = formatDate(date('Y-m-d')); // Domyślnie wybieramy dzisiejszą datę
    }

    // Pobierz zlecenia na wybraną datę
    $zlecenia = getZlecenia($selectedDate);
    ?>

    <p style="text-align: right; zindex: 1; margin-top: -10px;">Zalogowany użytkownik: <?php echo $_SESSION['username']; ?></p>
    <img style="zindex: -9; margin-top: -85px;" class="logo" src="img/logo.png" width="200px">
    <center><h2 style="margin-top: -70px;">Zarządzanie zleceniami</h2></center>

    <br>
    <div class="kalendarz"><center>
	<table>
		<tr>
			<td>
				<h3>Zlecenia na <?php echo formatDate($selectedDate); ?>:</h3>
			</td>
			<td>
				<div class="fun1">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
						<input type="date" name="date" value="<?php echo $selectedDate; ?>" required>
						<input type="submit" value="Wybierz" class="bzaloguj">
					</form>
				</div>
			</td>
			<td>
				<div class="fun2">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
						<input type="hidden" name="date" value="<?php echo date('Y-m-d', strtotime($selectedDate . ' -1 day')); ?>">
						<input type="submit" value="Poprzedni dzień" class="bzaloguj">
					</form>
				</div>
			</td>
			<td>
				<div class="fun3">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
						<input type="hidden" name="date" value="<?php echo date('Y-m-d', strtotime($selectedDate . ' +1 day')); ?>">
						<input type="submit" value="Następny dzień" class="bzaloguj">
					</form>
				</div>
			</td>
		</tr>
	</table>
	</center>
</div>

<table>
	<tr>
		<th>Id zlecenia</th>
		<th>Nazwa zlecenia</th>
		<th>Zleceniodawca</th>
		<th>Data dodania zlecenia</th>
		<th>Ilość</th>
		<th>Aktualnie wykonane</th>
		<th>Różnica</th>
		<th><img src="img/v.png" width="20" height="20"> / <img src="img/x.jpg" width="20" height="20"></th>
		<th>Ostatnio</th>
		<th>Akcje</th>
	</tr>
	<?php foreach ($zlecenia as $zlecenie) { ?>
		<tr>
			<td><?php echo $zlecenie['Id_zlecenia']; ?></td>
			<td><?php echo $zlecenie['nazwa_zlecenia']; ?></td>
			<td><?php echo $zlecenie['zleceniodawca']; ?></td>
			<td><?php echo $zlecenie['data_dodania_zlecenia']; ?></td>
			<td><?php echo $zlecenie['ilosc']; ?></td>
			<td><?php echo $zlecenie['wykonane'] ?? '0'; ?></td>
			<td><?php echo $zlecenie['wykonane'] - ($zlecenie['ilosc'] ?? 0); ?></td>
			<td><?php echo ($zlecenie['ilosc'] <= ($zlecenie['wykonane'] ?? 0)) ? '<img src="img/v.png" width="20" height="20">' : '<img src="img/x.jpg" width="20" height="20">'; ?></td>
			<td><?php echo $zlecenie['ostatnia_zmiana']; ?></td>
			<td>
				<a href="edytuj_zlecenie.php?id=<?php echo $zlecenie['Id_zlecenia']; ?>">Edytuj</a>
				<a href="usun_zlecenie.php?id=<?php echo $zlecenie['Id_zlecenia']; ?>">Usuń</a>
				<a href="stan_zlecenia.php?id=<?php echo $zlecenie['Id_zlecenia']; ?>">Sprawdź stan</a>
			</td>
		</tr>
	<?php } ?>
</table>
<br><br>

<center>

	<table style="width: 390px;">
		<form action="dodaj_zlecenie.php" method="POST">
			<input type="hidden" name="date" value="<?php echo $selectedDate; ?>">

			<tr>
				<th>Dodaj nowe zlecenie</th>
				<th></th>
			</tr>
			<tr>
				<td> <label for="nazwa">Nazwa zlecenia:</label></td>
				<td><input type="text" name="nazwa" required></td>
			</tr>
			<tr>
				<td><label for="ilosc">Ilość:</label></td>
				<td><input type="text" name="ilosc" required></td>
			</tr>
			<tr>
				<td><label for="zleceniodawca">Zleceniodawca:</label></td>
				<td><input type="text" name="zleceniodawca" required></td>
			</tr>
	</table><br>
	<input type="submit" value="Dodaj zlecenie" class="bzaloguj"><br><br>
	</form>


	<a href="index.php"><input type="submit" value="Wróć" class="bzaloguj"></a>
</center>
<br><br>
