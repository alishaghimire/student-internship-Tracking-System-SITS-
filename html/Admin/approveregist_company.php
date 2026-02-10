<?php
require_once "db_aadminconnect.php";

if (!isset($_POST['company_id'])) {
    echo "missing_id";
    exit;
}

$company_id = (int)$_POST['company_id'];

$sql = "UPDATE company_registration SET status='approved' WHERE company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>