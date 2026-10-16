<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "digitrack_1";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("SET time_zone = '+08:00'"); 

?>