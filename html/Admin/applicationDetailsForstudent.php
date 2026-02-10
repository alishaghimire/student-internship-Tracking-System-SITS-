<?php
session_start();
require_once "db_aadminconnect.php";

$application_id = intval($_GET['id'] ?? 0);
if ($application_id <= 0) { die("Invalid application ID."); }

$stmt = $conn->prepare("SELECT * FROM applications WHERE application_id=? LIMIT 1");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$app) { die("Application not found."); }

$stmt1 = $conn->prepare("SELECT * FROM application_step1 WHERE application_id=? LIMIT 1");
$stmt1->bind_param("i", $application_id);
$stmt1->execute();
$step1 = $stmt1->get_result()->fetch_assoc();
$stmt1->close();

$stmt2 = $conn->prepare("SELECT * FROM application_step2 WHERE application_id=? LIMIT 1");
$stmt2->bind_param("i", $application_id);
$stmt2->execute();
$step2 = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

$stmt3 = $conn->prepare("SELECT * FROM application_step3 WHERE application_id=? LIMIT 1");
$stmt3->bind_param("i", $application_id);
$stmt3->execute();
$step3 = $stmt3->get_result()->fetch_assoc();
$stmt3->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Application #<?php echo $app['application_id']; ?> Details</title>
  <link rel="stylesheet" href="ApplicationDetailsfostd.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <div class="page-wrapper">
    <a href="javascript:history.back()" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <div class="application-container">
      <header class="header">
        <h1><i class="fa-solid fa-file-lines"></i> Application #<?php echo $app['application_id']; ?></h1>
        <p>Status: <span class="status <?php echo strtolower($app['status']); ?>">
          <?php echo ucfirst($app['status']); ?>
        </span></p>
        <p>Submitted On: <?php echo date("M d, Y H:i", strtotime($app['created_at'])); ?></p>
      </header>

      <section class="application-section">
        <h3><i class="fa-solid fa-user"></i> Personal Information</h3>
        <?php if ($step1): ?>
          <ul>
            <li><strong>Full Name:</strong> <?php echo htmlspecialchars($step1['full_name']); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($step1['email']); ?></li>
            <li><strong>Phone:</strong> <?php echo htmlspecialchars($step1['phone']); ?></li>
            <li><strong>Date of Birth:</strong> <?php echo htmlspecialchars($step1['dob']); ?></li>
            <li><strong>LinkedIn:</strong> <?php echo htmlspecialchars($step1['linkedin']); ?></li>
          </ul>
        <?php else: ?>
          <p>No personal information found.</p>
        <?php endif; ?>
      </section>

      <section class="application-section">
        <h3><i class="fa-solid fa-graduation-cap"></i> Education & Experience</h3>
        <?php if ($step2): ?>
          <ul>
            <li><strong>Education Level:</strong> <?php echo htmlspecialchars($step2['education_level']); ?></li>
            <li><strong>University:</strong> <?php echo htmlspecialchars($step2['university']); ?></li>
            <li><strong>Graduation Year:</strong> <?php echo htmlspecialchars($step2['graduation_year']); ?></li>
            <li><strong>Experience Level:</strong> <?php echo htmlspecialchars($step2['experience_level']); ?></li>
            <li><strong>Availability:</strong> <?php echo htmlspecialchars($step2['availability']); ?></li>
            <li><strong>Cover Letter:</strong> <?php echo nl2br(htmlspecialchars($step2['cover_letter'])); ?></li>
          </ul>
        <?php else: ?>
          <p>No education/experience information found.</p>
        <?php endif; ?>
      </section>

      <section class="application-section">
        <h3><i class="fa-solid fa-file"></i> Documents</h3>
        <?php if ($step3): ?>
          <ul>
            <li><strong>Resume:</strong> <?php echo htmlspecialchars($step3['resume_path']); ?></li>
            <li><strong>Portfolio File:</strong> <?php echo htmlspecialchars($step3['portfolio_path']); ?></li>
            <li><strong>Portfolio URL:</strong> <a href="<?php echo htmlspecialchars($step3['portfolio_url']); ?>" target="_blank">
              <?php echo htmlspecialchars($step3['portfolio_url']); ?></a></li>
          </ul>
        <?php else: ?>
          <p>No documents uploaded.</p>
        <?php endif; ?>
      </section>
    </div>
  </div>
</body>
</html>