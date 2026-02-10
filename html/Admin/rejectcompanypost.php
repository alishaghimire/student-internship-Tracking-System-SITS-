<?php
include "db_aadminconnect.php";

$post_id = $_POST['post_id'];

$sql = "UPDATE postposition 
        SET admin_approval = 2
        WHERE id = '$post_id'";

$conn->query($sql);

echo "success";
?>