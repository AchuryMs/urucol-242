<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "urucol_242_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
