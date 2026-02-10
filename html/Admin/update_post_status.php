<?php
require_once 'db_aadminconnect.php';

if(isset($_POST['id'], $_POST['action'])){
    $id = intval($_POST['id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE postposition SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $action, $id);
    if($stmt->execute()){
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
}
?>
