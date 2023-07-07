<?php
// Dane uwierzytelniające bazy danych
$servername = "localhost"; // Adres serwera bazy danych (lokalny)
$username = "root"; // Nazwa użytkownika bazy danych
$password = ""; // Hasło użytkownika bazy danych
$dbname = "w64879_projekt"; // Nazwa bazy danych

// Utworzenie połączenia z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie czy połączenie zostało nawiązane poprawnie
if(mysqli_connect_errno()){
	echo "Połączenie nie zostało nawiązane!";
	exit();
}
echo "Połączenie zostało nawiązane!";

?>