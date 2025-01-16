<?php
$servername = "localhost";
$username = "root"; // Utilizatorul MySQL implicit
$password = ""; // Parola implicită pentru XAMPP este goală
$dbname = "hangman";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
