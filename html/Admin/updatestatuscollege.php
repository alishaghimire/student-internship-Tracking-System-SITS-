<?php
require_once 'db_aadminconnect.php';

if(isset($_POST['id']) && isset($_POST['action'])){
    $id = intval($_POST['id']);
    $action = $_POST['action'] === 'approve' ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE colleges SET status = ? WHERE college_id = ?");
    $stmt->bind_param("si", $action, $id);

    if($stmt->execute()){
        echo "success";
    } else {
        echo "error";
    }
}
?>