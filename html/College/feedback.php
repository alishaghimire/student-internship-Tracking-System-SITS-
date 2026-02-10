<?php
session_start();
require_once "collegedb_connection.php";

/* College auth */
if (!isset($_SESSION['college_id'])) {
    die("Unauthorized access");
}
$college_id = $_SESSION['college_id'];

/* Get entry_id */
if (!isset($_GET['entry_id'])) {
    die("Missing entry ID");
}
$entry_id = (int)$_GET['entry_id'];

$errors = [];
$success = "";

/* STEP 1: Get student_id from logbook */
$stmt = $conn->prepare("
    SELECT le.student_id
    FROM logbook_entries le
    JOIN students_registrtaion sr ON le.student_id = sr.student_id
    WHERE le.entry_id = ? AND sr.college_id = ?
");
$stmt->bind_param("ii", $entry_id, $college_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Invalid logbook entry");
}

$row = $res->fetch_assoc();
$student_id = $row['student_id'];

/* STEP 2: Get application_id + company_id for that student */
$appStmt = $conn->prepare("
    SELECT application_id, company_id
    FROM applications
    WHERE student_id = ?
    ORDER BY created_at DESC
    LIMIT 1
");
$appStmt->bind_param("i", $student_id);
$appStmt->execute();
$appRes = $appStmt->get_result();

if ($appRes->num_rows === 0) {
    die("No application found for this student");
}

$app = $appRes->fetch_assoc();
$application_id = $app['application_id'];
$company_id     = $app['company_id'];

/* STEP 3: Insert feedback */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $note_text = trim($_POST['note_text']);

    if (empty($note_text)) {
        $errors[] = "Feedback cannot be empty";
    }

    if (empty($errors)) {
        $insert = $conn->prepare("
            INSERT INTO review_notes (application_id, company_id, note_text)
            VALUES (?, ?, ?)
        ");
        $insert->bind_param("iis", $application_id, $company_id, $note_text);

        if ($insert->execute()) {
            $success = "Feedback submitted successfully";
        } else {
            $errors[] = "Database error";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>
<style>
body { background:#f4f6fb; font-family:Arial; }
.box {
  width:420px;
  margin:90px auto;
  background:#fff;
  padding:25px;
  border-radius:10px;
  box-shadow:0 10px 25px rgba(0,0,0,0.1);
}
textarea {
  width:100%;
  height:120px;
  padding:10px;
  border-radius:6px;
  border:1px solid #ccc;
}
button {
  width:100%;
  padding:10px;
  background:#4f46e5;
  color:#fff;
  border:none;
  border-radius:6px;
  margin-top:12px;
}
.success { background:#e0ffe7; padding:8px; margin-bottom:10px; }
.error { background:#ffe0e0; padding:8px; margin-bottom:10px; }
</style>
</head>
<body>

<div class="box">
  <h2>Feedback for Student</h2>

  <?php foreach ($errors as $e): ?>
    <div class="error"><?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <?php if ($success): ?>
    <div class="success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <textarea name="note_text" placeholder="Write feedback for this student..."></textarea>
    <button type="submit">Submit Feedback</button>
  </form>
</div>

</body>
</html>
