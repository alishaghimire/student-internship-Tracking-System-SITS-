<?php
include "pendingapproval.php";

$company_id = $_POST['company_id'];

$sql = "UPDATE company_registration 
        SET status = 'rejected'
        WHERE company_id = '$company_id'";

$conn->query($sql);

echo "success";
?>