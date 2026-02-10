<?php
session_start();
require_once "studentdbconnection.php";

/* ======================
   AUTH CHECK
====================== */
if (!isset($_SESSION['student_id'])) {
    header("Location: studentLogin.php");
    exit;
}

$student_id = (int) $_SESSION['student_id'];

/* ======================
   GET APPLICATION ID
====================== */
$application_id = isset($_GET['app_id']) ? (int)$_GET['app_id'] : 0;
if ($application_id <= 0) {
    die("Invalid application ID");
}

/* ======================
   FETCH APPLICATION + POST
====================== */
$stmt = $conn->prepare("
    SELECT a.*, p.title, p.department, p.location, p.deadline
    FROM applications a
    JOIN postposition p ON a.post_id = p.id
    WHERE a.application_id = ? AND a.student_id = ?
");
$stmt->bind_param("ii", $application_id, $student_id);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();

if (!$app) {
    die("Application not found or unauthorized.");
}

/* ======================
   FETCH APPLICATION STEPS
====================== */
$stmt = $conn->prepare("SELECT * FROM application_step1 WHERE application_id=?");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$s1 = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM application_step2 WHERE application_id=?");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$s2 = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM application_step3 WHERE application_id=?");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$s3 = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Details</title>
  <link rel="stylesheet" href="viewapplication.css">
</head>
<body>
<div class="container">

  <!-- Header -->
  <div class="header">
    <div class="profile">
      <div class="avatar"><?= strtoupper(substr($s1['full_name'],0,2)) ?></div>
      <div>
        <div class="name"><?= htmlspecialchars($s1['full_name']) ?></div>
        <div class="subtext">Applied on <?= date("M d, Y", strtotime($app['created_at'])) ?></div>
      </div>
    </div>
    <div class="status"><?= strtoupper($app['status']) ?></div>
  </div>

  <!-- Position -->
  <div class="position-card">
    <div>
      <small>Applied for Position</small>
      <h2><?= $app['title'] ?></h2>
      <small><?= $app['department'] ?> · <?= $app['location'] ?></small>
    </div>
    <div>
      <small>Application Deadline</small>
      <h3><?= date("M d, Y", strtotime($app['deadline'])) ?></h3>
    </div>
  </div>

  <!-- Tabs -->
  <div class="tabs">
    <div class="tab active">Overview</div>
    <div class="tab">Education</div>
    <div class="tab">Documents</div>
  </div>

  <!-- Tab Content -->
  <div class="tab-content">

    <!-- Overview Section -->
    <div id="overview" class="tab-pane active">
      <div class="section-title">Contact Information</div>
      <div class="info-grid">
        <div class="info-box"><span>Email</span> <?= $s1['email'] ?></div>
        <div class="info-box"><span>Phone</span> <?= $s1['phone'] ?></div>
        <div class="info-box"><span>Date of Birth</span> <?= $s1['dob'] ?></div>
        <div class="info-box"><span>LinkedIn</span> <a href="<?= $s1['linkedin'] ?>" target="_blank">View Profile</a></div>
      </div>

      <div class="section-title" style="margin-top:25px;">Cover Letter</div>
      <div class="cover-letter"><?= nl2br($s2['cover_letter']) ?></div>

      <div class="section-title" style="margin-top:25px;">Quick Information</div>
      <div class="quick-info">
        <div class="quick-box blue"><span>Experience Level</span><strong><?= $s2['experience_level'] ?></strong></div>
        <div class="quick-box green"><span>Availability</span><strong><?= $s2['availability'] ?></strong></div>
        <div class="quick-box purple"><span>Current Status</span><strong><?= $s2['education_level'] ?></strong></div>
      </div>
    </div>

    <!-- Education Section -->
    <div id="education" class="tab-pane">
      <h2>Educational Background</h2>
      <div class="edu-card">
        <strong><?= $s2['university'] ?></strong><br>
        Education Level: <?= $s2['education_level'] ?><br>
        Graduation Year: <?= $s2['graduation_year'] ?>
      </div>

      <h2 style="margin-top:20px;">Portfolio</h2>
      <?php if(!empty($s3['portfolio_url'])): ?>
      <div class="portfolio-card">
        <strong>Online Portfolio</strong><br>
        <a href="<?= $s3['portfolio_url'] ?>" target="_blank"><?= $s3['portfolio_url'] ?></a><br>
        <button onclick="window.open('<?= $s3['portfolio_url'] ?>','_blank')">Visit Portfolio</button>
      </div>
      <?php endif; ?>
    </div>

    <!-- Documents Section -->
    <div id="documents" class="tab-pane">
      <h2>Uploaded Documents</h2>
      <div class="documents">
        <?php if(!empty($s3['resume_path'])): ?>
        <div class="doc-item">
          <span>Resume / CV - <?= basename($s3['resume_path']) ?></span>
          <button onclick="window.location.href='<?= $s3['resume_path'] ?>'">Download</button>
        </div>
        <?php endif; ?>
        <?php if(!empty($s3['portfolio_path'])): ?>
        <div class="doc-item">
          <span>Portfolio File - <?= basename($s3['portfolio_path']) ?></span>
          <button onclick="window.location.href='<?= $s3['portfolio_path'] ?>'">Download</button>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>

</div>

<script>
  const tabs = document.querySelectorAll('.tabs .tab');
  const panes = document.querySelectorAll('.tab-pane');

  tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panes.forEach(p => p.classList.remove('active'));

      tab.classList.add('active');
      panes[index].classList.add('active');
    });
  });
</script>

</body>
</html>
