<?php
session_start();
require_once "dbconnectcompany.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}

$company_id = (int) $_SESSION['company_id'];
$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

if ($student_id <= 0) {
    die("Invalid student ID");
}

// Fetch student + application + position details
$stmt = $conn->prepare("
    SELECT 
        s1.full_name, s1.email, s1.phone, s1.dob, s1.linkedin,
        s2.education_level, s2.university, s2.graduation_year, s2.experience_level, s2.availability, s2.cover_letter,
        s3.resume_path, s3.portfolio_url, s3.portfolio_path,
        p.title AS position, p.department, p.location, p.deadline,
        a.status
    FROM applications a
    JOIN application_step1 s1 ON a.application_id = s1.application_id
    JOIN application_step2 s2 ON a.application_id = s2.application_id
    LEFT JOIN application_step3 s3 ON a.application_id = s3.application_id
    JOIN postposition p ON a.post_id = p.id
    WHERE a.application_id=? AND a.company_id=? AND a.status='shortlisted'
");
$stmt->bind_param("ii", $student_id, $company_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    die("Student not found or not shortlisted.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($student['full_name']) ?> – Profile</title>
<link rel="stylesheet" href="studentProfile.css">
</head>
<body>
<div class="profile-container">
  <div class="header">
    <div class="avatar"><?= strtoupper(substr($student['full_name'],0,2)) ?></div>
    <div class="student-info">
      <h1><?= htmlspecialchars($student['full_name']) ?></h1>
      <p><?= htmlspecialchars($student['email']) ?> | <?= htmlspecialchars($student['phone']) ?></p>
      <p>Applied for: <?= htmlspecialchars($student['position']) ?> (<?= htmlspecialchars($student['department']) ?>)</p>
      <p>Location: <?= htmlspecialchars($student['location']) ?></p>
      <p>Status: <span class="status"><?= ucfirst($student['status']) ?></span></p>
    </div>
  </div>

  <div class="sections">
    <div class="section">
      <h2>Education</h2>
      <p>University: <?= htmlspecialchars($student['university']) ?></p>
      <p>Education Level: <?= htmlspecialchars($student['education_level']) ?></p>
      <p>Graduation Year: <?= htmlspecialchars($student['graduation_year']) ?></p>
    </div>

    <div class="section">
      <h2>Experience & Availability</h2>
      <p>Experience Level: <?= htmlspecialchars($student['experience_level']) ?></p>
      <p>Availability: <?= htmlspecialchars($student['availability']) ?></p>
    </div>

    <div class="section">
      <h2>Cover Letter</h2>
      <p><?= nl2br(htmlspecialchars($student['cover_letter'])) ?></p>
    </div>

    <div class="section">
      <h2>Documents</h2>
      <?php if (!empty($student['resume_path'])): ?>
        <p>Resume: 
          <a href="download.php?file=<?= urlencode(basename($student['resume_path'])) ?>">Download</a>
        </p>
      <?php endif; ?>
      <?php if (!empty($student['portfolio_url'])): ?>
        <p>Portfolio (URL): 
          <a href="<?= htmlspecialchars($student['portfolio_url']) ?>" target="_blank">
            <?= htmlspecialchars($student['portfolio_url']) ?>
          </a>
        </p>
      <?php endif; ?>
      <?php if (!empty($student['portfolio_path'])): ?>
        <p>Portfolio (File): 
<a href="download.php?file=<?= urlencode(basename($student['resume_path'])) ?>">Download</a>        </p>
      <?php endif; ?>
    </div>
  </div>

  <div class="actions">
    <a href="javascript:window.history.back()" class="btn">Back</a>
  </div>
</div>
</body>
</html>