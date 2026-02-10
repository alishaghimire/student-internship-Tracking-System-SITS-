<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projec1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Diagnostic check to confirm the database you're connected to
// echo "Connected to: " . $conn->query("SELECT DATABASE()")->fetch_row()[0];  // This will print the name of the database you're connected to
?>
