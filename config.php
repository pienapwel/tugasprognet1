<?php
$servername = "localhost";
$username = "root";
$password = "Bunga_2005";
$dbname = "mahasiswa_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
