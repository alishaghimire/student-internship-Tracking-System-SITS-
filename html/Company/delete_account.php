<?php
session_start();
require_once "dbconnectcompany.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}

$company_id = $_SESSION['company_id'];

// Delete related postpositions
$conn->query("DELETE FROM postposition WHERE company_id = $company_id");

// Delete company settings
$conn->query("DELETE FROM company_settings WHERE company_id = $company_id");

// Delete company account
$conn->query("DELETE FROM company_registration WHERE id = $company_id");

session_destroy();
header("Location: index.php");
exit;
?>