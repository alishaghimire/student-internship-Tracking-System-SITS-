<?php
// Start session to access $_SESSION
session_start();

// Include your database connection
require_once "dbconnectcompany.php";

// Check if user is logged in
if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}

// Get company ID and form inputs
$company_id = $_SESSION['company_id'];
$bg   = $_POST['theme_bg'] ?? '#f4f6f8';
$font = $_POST['theme_font'] ?? 'Arial';

// Check if settings exist
$stmt = $conn->prepare("SELECT id FROM company_settings WHERE company_id=?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing settings
    $stmt = $conn->prepare("UPDATE company_settings SET theme_bg=?, theme_font=? WHERE company_id=?");
    $stmt->bind_param("ssi", $bg, $font, $company_id);
} else {
    // Insert new settings
    $stmt = $conn->prepare("INSERT INTO company_settings (company_id, theme_bg, theme_font) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $company_id, $bg, $font);
}

$stmt->execute();
$stmt->close();

// Redirect back to dashboard after saving
header("Location: CDashboard.php");
exit;
