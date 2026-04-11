<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "fernanda", "1234", "app_db");

if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM usuarios");

while($row = $result->fetch_assoc()) {
    echo $row['nombre'] . " - " . $row['correo'] . "<br>";
}
?><?php 
$conn = new mysqli("localhost", "fernanda", "1234", "app_db");

$result = $conn->query("SELECT * FROM usuarios");

echo "<h1>Lista de usuarios</h1>";

while($row = $result->fetch_assoc()){
    echo $row['nombre'] . " - " . $row['correo'] . "<br>";
}
?>

