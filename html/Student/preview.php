<?php
session_start();
require_once "studentdbconnection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: studentLogin.php");
    exit;
}

$student_id = $_SESSION['student_id'];

/* FETCH PERSONAL DETAILS */
$stmt = $conn->prepare("SELECT * FROM student_profile WHERE student_id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$personal = $stmt->get_result()->fetch_assoc();

/* FETCH CONTACT DETAILS */
$stmt = $conn->prepare("SELECT * FROM contact_details WHERE student_id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$contact = $stmt->get_result()->fetch_assoc();

/* FETCH EDUCATION DETAILS */
$stmt = $conn->prepare("SELECT * FROM education_details WHERE student_id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$education = $stmt->get_result()->fetch_assoc();

/* FIXED PROFILE PIC PATH */
$profilePic = "";
if (!empty($personal['profilePic'])) {
    // Correct path based on your real folder structure
    $profilePic = "/project/html/Student/uploads/" . $personal['profilePic'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preview Details</title>
<link rel="stylesheet" href="/project/css/Student/preview.css">

<style>
.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}
.profile-img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 10px; /* square with soft corners */
    border: 3px solid #ddd;
}
.profile-info h2 {
    margin: 0;
    font-size: 1.6rem;
    font-weight: 700;
    color: #333;
}
.profile-info p {
    margin: 5px 0 0;
    font-size: 1rem;
    color: #666;
}
.preview-section {
    margin-bottom: 20px;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.preview-row {
    display: flex;
    gap: 10px;
    margin-bottom: 8px;
}
.preview-row span {
    font-weight: 600;
    color: #444;
}
</style>
</head>

<body>
<div class="form-container">

<h2 class="form-title">Preview Your Details</h2>

<!-- PROFILE HEADER -->
<div class="profile-header">

    <?php if ($profilePic): ?>
        <img src="<?= $profilePic ?>" class="profile-img" alt="Profile Picture">
    <?php else: ?>
        <div class="profile-img" style="background:#eee;display:flex;align-items:center;justify-content:center;color:#777;">
            No Image
        </div>
    <?php endif; ?>

    <div class="profile-info">
        <h2><?= ($personal['fname'] ?? '') . " " . ($personal['lname'] ?? '') ?></h2>
        <p><?= $contact['email'] ?? '' ?></p>
    </div>

</div>

<!-- PERSONAL DETAILS -->
<div class="preview-section">
    <h3>Personal Details</h3>
    <div class="preview-row"><span>First Name:</span> <p><?= $personal['fname'] ?? '' ?></p></div>
    <div class="preview-row"><span>Last Name:</span> <p><?= $personal['lname'] ?? '' ?></p></div>
    <div class="preview-row"><span>Date of Birth:</span> <p><?= $personal['dob'] ?? '' ?></p></div>
    <div class="preview-row"><span>Gender:</span> <p><?= $personal['gender'] ?? '' ?></p></div>
    <div class="preview-row"><span>Nationality:</span> <p><?= $personal['nationality'] ?? '' ?></p></div>
    <div class="preview-row"><span>Marital Status:</span> <p><?= $personal['marital'] ?? '' ?></p></div>
</div>

<!-- CONTACT DETAILS -->
<div class="preview-section">
    <h3>Contact Details</h3>
    <div class="preview-row"><span>Email:</span> <p><?= $contact['email'] ?? '' ?></p></div>
    <div class="preview-row"><span>Phone:</span> <p><?= $contact['phone'] ?? '' ?></p></div>
    <div class="preview-row"><span>Alternate Phone:</span> <p><?= $contact['altPhone'] ?? '' ?></p></div>
    <div class="preview-row"><span>Country:</span> <p><?= $contact['country'] ?? '' ?></p></div>
    <div class="preview-row"><span>State:</span> <p><?= $contact['state'] ?? '' ?></p></div>
    <div class="preview-row"><span>City:</span> <p><?= $contact['city'] ?? '' ?></p></div>
    <div class="preview-row"><span>Full Address:</span> <p><?= $contact['fullAddress'] ?? '' ?></p></div>
</div>

<!-- EDUCATION DETAILS -->
<div class="preview-section">
    <h3>Education Details</h3>
    <div class="preview-row"><span>Education Level:</span> <p><?= $education['level'] ?? '' ?></p></div>
    <div class="preview-row"><span>Major:</span> <p><?= $education['major'] ?? '' ?></p></div>
    <div class="preview-row"><span>Institution:</span> <p><?= $education['institution'] ?? '' ?></p></div>
    <div class="preview-row"><span>University:</span> <p><?= $education['university'] ?? '' ?></p></div>
    <div class="preview-row"><span>Start Year:</span> <p><?= $education['startYear'] ?? '' ?></p></div>
    <div class="preview-row"><span>End Year:</span> <p><?= $education['endYear'] ?? '' ?></p></div>
    <div class="preview-row"><span>Grade:</span> <p><?= $education['grade'] ?? '' ?></p></div>
</div>

<button type="button" class="back-btn" onclick="window.location='education.php'">Back</button>
<button class="submit-btn">Submit</button>

</div>
</body>
</html>