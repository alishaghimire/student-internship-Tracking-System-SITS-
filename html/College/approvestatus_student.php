<?php
session_start();
require_once "collegedb_connection.php";

if (!isset($_SESSION['college_id'])) {
    die("Unauthorized");
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}

$student_id = (int) $_GET['id'];
$college_id = $_SESSION['college_id'];

// Correct table name
$sql = "UPDATE students_registrtaion 
        SET status = 'approved' 
        WHERE student_id = ? AND college_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $college_id);
$stmt->execute();

header("Location: studentdetails.php");
exit;
?>
