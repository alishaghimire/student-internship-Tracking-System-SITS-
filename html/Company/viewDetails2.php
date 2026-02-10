<?php
session_start();
require_once "dbconnectcompany.php";

// =======================
// AUTH CHECK
// =======================
$company_id = isset($_SESSION['company_id']) ? (int)$_SESSION['company_id'] : 0;
if ($company_id <= 0) {
    header("Location: companyLogin.php");
    exit;
}

// =======================
// GET APPLICATION ID from GET
// =======================
$application_id = isset($_GET['application_id']) ? (int)$_GET['application_id'] : 0;
if ($application_id <= 0) {
    die("Invalid application ID");
}

// =======================
// FETCH APPLICATION + POST for THIS COMPANY
// =======================
// FIX: check p.company_id instead of a.company_id
$stmt = $conn->prepare("
    SELECT a.*, p.title, p.department, p.location, p.deadline, p.company_id
    FROM applications a
    JOIN postposition p ON a.post_id = p.id
    WHERE a.application_id = ? AND p.company_id = ?
");
$stmt->bind_param("ii", $application_id, $company_id);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$app) {
    die("Unauthorized access or application not found.");
}

// =======================
// FETCH APPLICATION STEPS
// =======================
function fetchStep($conn, $table, $application_id) {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE application_id=?");
    if (!$stmt) return null;
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

$s1 = fetchStep($conn, "application_step1", $application_id) ?? [];
$s2 = fetchStep($conn, "application_step2", $application_id) ?? [];
$s3 = fetchStep($conn, "application_step3", $application_id) ?? [];

// =======================
// REVIEW NOTES
// =======================
$stmt = $conn->prepare("SELECT * FROM review_notes WHERE application_id=? AND company_id=? ORDER BY created_at DESC");
$stmt->bind_param("ii", $application_id, $company_id);
$stmt->execute();
$notes_result = $stmt->get_result();
$review_notes = [];
while ($row = $notes_result->fetch_assoc()) {
    $review_notes[] = $row;
}
$stmt->close();

// =======================
// HANDLE STATUS ACTIONS (AJAX POST)
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $app_id_post = isset($_POST['application_id']) ? (int)$_POST['application_id'] : 0;
    if ($app_id_post <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid application ID"]);
        exit;
    }

    $map = [
        'shortlist' => 'shortlisted',
        'reject'    => 'rejected',
        'reviewed'  => 'reviewed',
        'reset'     => 'pending',
    ];

    $action = $_POST['action'];
    if (!isset($map[$action])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
        exit;
    }

    $new_status = $map[$action];

    $stmt = $conn->prepare("
        UPDATE applications a
        JOIN postposition p ON a.post_id = p.id
        SET a.status = ?
        WHERE a.application_id = ? AND p.company_id = ?
    ");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Prepare failed: ".$conn->error]);
        exit;
    }

    $stmt->bind_param("sii", $new_status, $app_id_post, $company_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => $new_status]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Update failed: ".$stmt->error]);
    }

    $stmt->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Details</title>
  <link rel="stylesheet" href="viewdetails2.css">
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
    <div class="tab">Notes & Actions</div>
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

    <!-- Notes & Actions Section -->
    <div id="notes" class="tab-pane">
            <h2 style="margin-top:20px;">Actions</h2>
      <button>Send Email to Applicant</button>
      <button>Schedule Interview</button>
      <button>Download All Documents</button>
    </div>
  </div>

  <!-- Actions (always at bottom) -->
  <div class="status-actions">
    <button type="button" class="reviewed-btn" data-action="reviewed">Mark as Reviewed</button>
    <button type="button" class="shortlist-btn" data-action="shortlist">Shortlist</button>
    <button type="button" class="reject-btn" data-action="reject">Reject</button>
    <button type="button" class="reset-btn" data-action="reset">Reset to Pending</button>
  </div>

</div>
<div style="margin: 20px 0;">
  <button onclick="window.history.back()" style="...">← Back to Applications</button>
</div>


<script>
// AJAX status update
function postAction(action) {
  const formData = new FormData();
  formData.append('action', action);
  formData.append('application_id', <?= $application_id ?>);

  fetch(window.location.href, {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status) {
      const badge = document.querySelector('.header .status');
      if (badge) badge.textContent = data.status.toUpperCase();
    } else if (data.error) {
      alert(data.error);
    }
  })
  .catch(err => {
    alert('Could not update status. Please try again.');
    console.error(err);
  });
}

// Attach postAction to all buttons
document.querySelectorAll('.reviewed-btn, .shortlist-btn, .reject-btn, .reset-btn')
.forEach(btn => {
  btn.addEventListener('click', () => {
    const action = btn.dataset.action;
    postAction(action);
  });
});

// Tab switching logic
document.querySelectorAll('.tab').forEach((tab, index) => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.querySelectorAll('.tab-pane')[index].classList.add('active');
  });
});
</script>

</body>
</html>