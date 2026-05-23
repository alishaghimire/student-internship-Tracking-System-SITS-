<?php
session_start();
require_once "dbconnectcompany.php";

// Check login
$company_id = isset($_SESSION['company_id']) ? (int)$_SESSION['company_id'] : 0;
if ($company_id <= 0) {
    die("Unauthorized");
}

// Get file parameter
$file = $_GET['file'] ?? '';
if (!$file) {
    die("No file specified.");
}

// Only allow safe filenames
$filename = basename($file); // strips any path
$allowed_extensions = ['pdf','doc','docx'];
if (!preg_match('/^[a-zA-Z0-9_\-]+\.(pdf|docx|doc)$/', $filename)) {
    die("Invalid file name.");
}

// Build path to Student/uploads
$filepath = realpath(__DIR__ . '/../Student/uploads/' . $filename);

if (!$filepath || !file_exists($filepath)) {
    die("File not found: " . $filepath);
}

// Send headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;
?>