<?php
session_start();

// Sprawdź, czy użytkownik jest zalogowany
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    // Przekieruj użytkownika do strony logowania, jeśli nie jest zalogowany
    header("Location: login.php");
    exit();
}

if (isset($_POST["logout"])) {
    // Tutaj można umieścić dodatkowe działania przed wylogowaniem, jeśli są potrzebne

    // Usuń tylko konkretne zmienne sesyjne
    unset($_SESSION["username"]);
    // unset($_SESSION["other_variable"]); // Można dodać inne zmienne sesyjne, jeśli istnieją

    // Przekieruj użytkownika na stronę logowania
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css\index.css">
    <title>Strona główna</title>
</head>
<body>
    <img class="logo" src="img/logo.png" width="400px">
    <div class="kontener">
    <h1>Witaj, <?php echo $username; ?>!</h1>
    <a href="zarzadzaj-zleceniami.php" class="button"><p class="napis">Zarządzaj zleceniami</p></a>
    <a href="zarzadzaj-stanem-magazynowym.php" class="button"><p class="napis">Zarządzaj stanem magazynowym</p></a>
    <a href="raporty.php" class="button"><p class="napis">Raporty</p></a>   
    <a href="zarzadzaj-pracownikami.php?session=<?php echo session_id(); ?>" class="button"><p class="napis">Zarządzaj pracownikami</p></a>
    
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input class="bwyloguj" type="submit" name="logout" value="Wyloguj">
    </form>
    </div>
</body>
</html>
