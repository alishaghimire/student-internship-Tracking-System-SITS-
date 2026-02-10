<?php
session_start();
require_once "studentdbconnection.php";

$student_id = $_SESSION['student_id'] ?? null;

if (!$student_id) {
    echo "<p style='color:red;'>User not logged in.</p>";
    exit;
}

// Fetch saved personal details
$stmt = $conn->prepare("SELECT * FROM student_profile WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p style='color:red;'>No personal details found.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 
<div class="personal-view-box">

    <h2 class="section-title">Personal Details</h2>

    <div class="detail-row">
        <label>First Name:</label>
        <span><?= htmlspecialchars($data['fname']); ?></span>
    </div>

    <div class="detail-row">
        <label>Last Name:</label>
        <span><?= htmlspecialchars($data['lname']); ?></span>
    </div>

    <div class="detail-row">
        <label>Date of Birth:</label>
        <span><?= htmlspecialchars($data['dob']); ?></span>
    </div>

    <div class="detail-row">
        <label>Gender:</label>
        <span><?= htmlspecialchars($data['gender']); ?></span>
    </div>

    <div class="detail-row">
        <label>Nationality:</label>
        <span><?= htmlspecialchars($data['nationality']); ?></span>
    </div>

    <div class="detail-row">
        <label>Marital Status:</label>
        <span><?= htmlspecialchars($data['marital']); ?></span>
    </div>

    <div class="status uploaded">Uploaded</div>

    <button class="edit-btn" onclick="loadEdit('personal')">Edit</button>

</div>

<style>
.personal-view-box {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    width: 100%;
}

.section-title {
    font-size: 22px;
    margin-bottom: 15px;
    color: #4c4cff;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.detail-row label {
    font-weight: 600;
    color: #333;
}

.detail-row span {
    color: #555;
}

.status {
    margin-top: 15px;
    padding: 8px 12px;
    display: inline-block;
    border-radius: 6px;
    font-weight: 600;
}

.uploaded {
    background: #e0ffe0;
    color: #2e7d32;
}

.edit-btn {
    margin-top: 20px;
    padding: 10px 18px;
    background: #4c4cff;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.edit-btn:hover {
    background: #3a3ad9;
}
</style>
   
</body>
</html>