<?php
$servername = "localhost";
$username = "root";
$password = "SmartPassword";
$database = "StealPlaze";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
?>