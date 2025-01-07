<?php
$host = "localhost";
$dbname = "blog";
$username = "root";
$password = "1234";

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
