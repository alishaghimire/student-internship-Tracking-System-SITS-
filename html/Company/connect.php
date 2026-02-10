<?php
$servername = "localhost";
$username = "root";   // your DB username
$password = "";       // your DB password
$dbname = "company";  // This should be 'project', not 'sits'

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Diagnostic check to confirm the database you're connected to
echo "Connected to: " . $conn->query("SELECT DATABASE()")->fetch_row()[0];  // This will print the name of the database you're connected to
?>
