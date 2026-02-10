<?php
session_start();
require_once "collegedb_connection.php";

if (!isset($_SESSION['college_id'])) {
    http_response_code(401);
    exit("Unauthorized");
}

$company_id = intval($_SESSION['college_id']); // college acting as reviewer
$application_id = intval($_POST['application_id'] ?? 0);
$note_text = trim($_POST['note_text'] ?? '');

if ($application_id <= 0 || empty($note_text)) {
    http_response_code(400);
    exit("Invalid input");
}

/* OPTIONAL: verify application exists */
$check = $conn->prepare("SELECT application_id FROM applications WHERE application_id=?");
$check->bind_param("i", $application_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    exit("Application not found");
}
$check->close();

/* INSERT REVIEW (MATCHES YOUR TABLE EXACTLY) */
$stmt = $conn->prepare("
    INSERT INTO review_notes (application_id, company_id, note_text)
    VALUES (?, ?, ?)
");

$stmt->bind_param("iis", $application_id, $company_id, $note_text);

if ($stmt->execute()) {
    echo "Review saved successfully";
} else {
    echo "Database error";
}

$stmt->close();
$conn->close();
