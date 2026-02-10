<?php
session_start();
require_once "db_aadminconnect.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['role'] !== 'super') {
    header("Location: AdminLogin.php");
    exit;
}

$admin_id = intval($_GET['id'] ?? 0);

if ($admin_id > 0) {
    $stmt = $conn->prepare("UPDATE admin_users SET status='Inactive' WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->close();
}

// Refresh the same page the link was clicked from
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;