<?php
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Pending approvals
$pendingCompanies = $conn->query("SELECT COUNT(*) AS cnt FROM company_registration WHERE status='Pending'")->fetch_assoc()['cnt'];
$pendingColleges  = $conn->query("SELECT COUNT(*) AS cnt FROM colleges WHERE status='Pending'")->fetch_assoc()['cnt'];

// If postposition has no status column, use deadline logic
$pendingPositions = $conn->query("SELECT COUNT(*) AS cnt FROM postposition WHERE deadline < CURDATE()")->fetch_assoc()['cnt'];
$totalPending     = $pendingCompanies + $pendingColleges + $pendingPositions;

// Active companies
$activeCompanies = $conn->query("SELECT COUNT(*) AS cnt FROM company_registration WHERE status='Approved'")->fetch_assoc()['cnt'];

// Total students
$totalStudents = $conn->query("SELECT COUNT(*) AS cnt FROM students_registrtaion")->fetch_assoc()['cnt'];

// Active positions (still open)
$activePositions = $conn->query("SELECT COUNT(*) AS cnt FROM postposition WHERE deadline >= CURDATE()")->fetch_assoc()['cnt'];

// Recent submissions (last 3 positions)
$recentSubmissions = $conn->query("
    SELECT p.title, p.company_name, p.created_at
    FROM postposition p
    ORDER BY p.created_at DESC
    LIMIT 3
");

// Collect activity items
$activities = [];

// Companies – no created_at, use company_id
$res = $conn->query("SELECT company_name, company_id FROM company_registration ORDER BY company_id DESC LIMIT 5");
while($row = $res->fetch_assoc()) {
    $activities[] = [
        'type' => 'company',
        'title' => 'New company registered',
        'detail' => $row['company_name'],
        'time_raw' => $row['company_id'],
        'time_display' => 'ID '.$row['company_id']
    ];
}

// Colleges – has created_at
$res = $conn->query("SELECT college_name, created_at FROM colleges ORDER BY created_at DESC LIMIT 5");
while($row = $res->fetch_assoc()) {
    $activities[] = [
        'type' => 'college',
        'title' => 'New college registered',
        'detail' => $row['college_name'],
        'time_raw' => strtotime($row['created_at']),
        'time_display' => date("M d, H:i", strtotime($row['created_at']))
    ];
}

// Students – no created_at, use student_id
$res = $conn->query("SELECT full_name, student_id FROM students_registrtaion ORDER BY student_id DESC LIMIT 5");
while($row = $res->fetch_assoc()) {
    $activities[] = [
        'type' => 'student',
        'title' => 'New student enrolled',
        'detail' => $row['full_name'],
        'time_raw' => $row['student_id'],
        'time_display' => 'ID '.$row['student_id']
    ];
}

// Positions – has created_at
$res = $conn->query("SELECT title, company_name, created_at FROM postposition ORDER BY created_at DESC LIMIT 5");
while($row = $res->fetch_assoc()) {
    $activities[] = [
        'type' => 'position',
        'title' => 'New internship posted',
        'detail' => $row['title'].' — '.$row['company_name'],
        'time_raw' => strtotime($row['created_at']),
        'time_display' => date("M d, H:i", strtotime($row['created_at']))
    ];
}

// Sort by recency
usort($activities, function($a, $b) {
    return $b['time_raw'] <=> $a['time_raw'];
});

// Keep only 5 most recent
$activities = array_slice($activities, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="/SITS/Css/Admin/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<section class="dashboard">

  <!-- KPI Cards -->
  <div class="cards-row">
    <div class="card kpi">
      <div class="icon orange"><i class="fa-solid fa-clock"></i></div>
      <h3>Pending Approvals</h3>
      <p class="value"><?= $totalPending ?> <span class="green"><?= $pendingPositions ?> new</span></p>
    </div>

    <div class="card kpi">
      <div class="icon purple"><i class="fa-solid fa-building"></i></div>
      <h3>Active Companies</h3>
      <p class="value"><?= $activeCompanies ?></p>
    </div>

    <div class="card kpi">
      <div class="icon blue"><i class="fa-solid fa-user-graduate"></i></div>
      <h3>Total Students</h3>
      <p class="value"><?= $totalStudents ?></p>
    </div>

    <div class="card kpi">
      <div class="icon green-bg"><i class="fa-solid fa-briefcase"></i></div>
      <h3>Active Positions</h3>
      <p class="value"><?= $activePositions ?> <span class="green"><?= $pendingPositions ?> pending</span></p>
    </div>
  </div>

  <!-- Submissions + Activity -->
  <div class="content-row">

    <!-- Recent Submissions -->
    <div class="card submissions">
      <h3>Recent Submissions</h3>
      <?php while($row = $recentSubmissions->fetch_assoc()): ?>
        <div class="submission-item">
          <div class="avatar pink"><?= strtoupper(substr($row['company_name'],0,2)) ?></div>
          <div class="text">
            <strong><?= htmlspecialchars($row['title']) ?></strong>
            <p><?= htmlspecialchars($row['company_name']) ?></p>
          </div>
          <button id="reviewPostsBtn" class="review-btn">Review</button>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- System Activity -->
    <div class="card system-activity">
      <h3>System Activity</h3>
      <?php foreach($activities as $act): ?>
        <div class="activity">
          <?php if($act['type']=='company'): ?>
            <span class="activity-icon blue"><i class="fa-solid fa-building-circle-check"></i></span>
          <?php elseif($act['type']=='college'): ?>
            <span class="activity-icon purple"><i class="fa-solid fa-sitemap"></i></span>
          <?php elseif($act['type']=='student'): ?>
            <span class="activity-icon green"><i class="fa-solid fa-users"></i></span>
          <?php elseif($act['type']=='position'): ?>
            <span class="activity-icon orange"><i class="fa-solid fa-briefcase"></i></span>
          <?php endif; ?>
          <div>
            <strong><?= htmlspecialchars($act['title']) ?></strong>
            <p><?= htmlspecialchars($act['detail']) ?></p>
          </div>
          <span class="time"><?= $act['time_display'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <button id="reviewPostsBtnQA" class="quick-action-btn"><i class="fa-solid fa-clock-rotate-left"></i> Review Posts</button>
    <button id="viewCollegeBtn" class="quick-action-btn"><i class="fa-solid fa-university"></i> View College</button>
    <button id="viewCompaniesBtn" class="quick-action-btn"><i class="fa-solid fa-building"></i> View Companies</button>
    <!-- Removed View Reports -->
  </div>

</section>

<script>
// Hook Quick Action buttons to switch panels in AdminDashboard
document.addEventListener("DOMContentLoaded", () => {
  const parentWindow = window.parent; // AdminDashboard.php is the parent

  function switchPanel(panelId) {
    if (parentWindow && parentWindow.showPanel) {
      parentWindow.showPanel(panelId);
      if (parentWindow.setActive) {
        parentWindow.setActive(panelId);
      }
    }
  }

  document.getElementById("reviewPostsBtnQA").addEventListener("click", () => {
    switchPanel("approvalContent");
  });

  document.getElementById("viewCollegeBtn").addEventListener("click", () => {
    switchPanel("departmentsContent");
  });

  document.getElementById("viewCompaniesBtn").addEventListener("click", () => {
    switchPanel("companiesContent");
  });
});
</script>

</body>
</html>