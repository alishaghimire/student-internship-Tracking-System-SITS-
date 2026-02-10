<?php
session_start();
require_once "collegedb_connection.php";

/* Security check */
if (!isset($_SESSION['college_id'])) {
    die("Unauthorized Access");
}

/* Validate student id */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}

$student_id = (int) $_GET['id'];

/* Reject student */
$sql = "UPDATE student 
        SET status = 'rejected' 
        WHERE student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();

/* Redirect back to dashboard */
header("Location: studentdetails.php");
exit;
?>
