<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to student login page
header("Location: studentLogin.php");
exit;
?>