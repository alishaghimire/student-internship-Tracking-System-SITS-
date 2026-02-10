<?php
require_once "collegedb_connection.php";
session_start();

/* ======================
   AUTH CHECK
====================== */
if (!isset($_SESSION['college_id'])) {
    die("Unauthorized Access");
}

if (!isset($_GET['id'])) {
    die("Invalid Student Request");
}

$college_id = (int) $_SESSION['college_id'];
$student_id = (int) $_GET['id'];

/* ======================
   FETCH SINGLE STUDENT
====================== */
$sql = "SELECT student_id, student_code, full_name, email, phone, major, college_name, status
        FROM students_registrtaion
        WHERE student_id = ?
        AND college_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $college_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Student not found or access denied");
}

$row = $result->fetch_assoc();
$stmt->close();

$statusClass = strtolower($row['status']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Details</title>

<style>
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --success: #16a34a;
    --pending: #f59e0b;
    --rejected: #dc2626;
    --bg: #f4f6fb;
    --border: #e5e7eb;
}

* {
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}

body {
    background: var(--bg);
    padding: 40px;
    margin: 0;
}

.container {
    max-width: 850px;
    margin: auto;
}

.student-card {
    background: white;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

/* Header */
.card-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 30px;
    text-align: center;
}

.card-header h2 {
    margin: 0;
    font-size: 28px;
}

.student-id {
    opacity: 0.9;
    margin-top: 6px;
}

/* Status */
.status-box {
    text-align: center;
    margin: 25px 0;
}

.status {
    display: inline-block;
    padding: 12px 30px;
    font-size: 22px;
    font-weight: bold;
    border-radius: 8px;
}

.status.approved {
    background: #dcfce7;
    color: var(--success);
    border: 2px solid var(--success);
}

.status.pending {
    background: #fef3c7;
    color: var(--pending);
    border: 2px solid var(--pending);
}

.status.rejected {
    background: #fee2e2;
    color: var(--rejected);
    border: 2px solid var(--rejected);
}

/* Info Grid */
.info {
    padding: 30px;
}

.row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.field label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 600;
}

.field div {
    background: #f9fafb;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid var(--border);
    font-size: 17px;
}

/* College Box */
.college-box {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 20px;
    text-align: center;
    margin: 25px;
    border-radius: 10px;
}

.college-box h3 {
    margin: 0 0 10px;
    color: var(--primary-dark);
}

/* Back Button */
.back-btn {
    display: inline-block;
    margin: 25px auto 0;
    background: var(--primary);
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}

.back-btn:hover {
    background: var(--primary-dark);
}

@media (max-width: 700px) {
    .row {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="student-card">

        <!-- HEADER -->
        <div class="card-header">
            <h2><?= htmlspecialchars($row['full_name']) ?></h2>
            <div class="student-id">ID: <?= htmlspecialchars($row['student_code']) ?></div>
        </div>

        <!-- STATUS -->
        <div class="status-box">
            <div class="status <?= $statusClass ?>">
                <?= strtoupper($row['status']) ?>
            </div>
        </div>

        <!-- DETAILS -->
        <div class="info">
            <div class="row">
                <div class="field">
                    <label>Full Name</label>
                    <div><?= htmlspecialchars($row['full_name']) ?></div>
                </div>
                <div class="field">
                    <label>Student ID</label>
                    <div><?= htmlspecialchars($row['student_code']) ?></div>
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <label>Email</label>
                    <div><?= htmlspecialchars($row['email']) ?></div>
                </div>
                <div class="field">
                    <label>Phone</label>
                    <div><?= htmlspecialchars($row['phone']) ?></div>
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <label>Major</label>
                    <div><?= htmlspecialchars($row['major']) ?></div>
                </div>
                <div class="field">
                    <label>College</label>
                    <div><?= htmlspecialchars($row['college_name']) ?></div>
                </div>
            </div>
        </div>

        <!-- COLLEGE -->
        <div class="college-box">
            <h3>Applied College</h3>
            <strong><?= htmlspecialchars($row['college_name']) ?></strong>
        </div>

        <!-- BACK -->
        <div style="text-align:center;">
            <a href="studentdetails.php" class="back-btn">← Back to Students</a>
        </div>

    </div>

</div>

</body>
</html>
