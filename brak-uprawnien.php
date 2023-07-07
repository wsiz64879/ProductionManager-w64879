<?php
// Sprawdzenie, czy użytkownik jest zalogowany
session_start();
if (!isset($_SESSION['username'])) {
    // Użytkownik nie jest zalogowany, przekierowanie do strony login.php po 5 sekundach
    header("refresh:5;url=login.php");
    echo "Brak uprawnień do wyświetlenia tej strony. Zostaniesz przekierowany na stronę logowania.";
    exit();
} else {
    // Użytkownik jest zalogowany, przekierowanie do strony index.php po 5 sekundach
    header("refresh:5;url=index.php");
    echo "Brak uprawnień do wyświetlenia tej strony. Zostaniesz przekierowany na stronę główną.";
    exit();
}
?>
