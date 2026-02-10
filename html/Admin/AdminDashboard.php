<?php
session_start();
require_once "db_aadminconnect.php"; // adjust to your DB connection file

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}

// Grab role from session
$role = $_SESSION['role'] ?? 'admin'; // default to admin if not set
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Portal Dashboard</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="/project/css/Admin/admindasboard.css">
</head>
<body>

<!-- Top Navigation -->
<header class="top-nav">
  <div class="left">
    <div class="logo-circle"><i class="fa-solid fa-graduation-cap"></i></div>
    <div class="title">
      <h2>Admin Portal</h2>
      <p>Admin Dashboard</p>
    </div>
  </div>


  <div class="right">
    <form action="logout.php" method="post" style="display:inline;">
      <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
    </form>
  </div>
</header>

<!-- Menu Bar -->
<nav class="menu-bar">
  <button class="active" data-target="dashboardContent"><i class="fa-solid fa-house"></i> Dashboard</button>
  <button data-target="approvalContent"><i class="fa-solid fa-hourglass-half"></i> Pending Approval </button>
  <button data-target="companiesContent"><i class="fa-solid fa-building"></i> View Companies</button>
  <button data-target="departmentsContent"><i class="fa-solid fa-sitemap"></i> View College</button>
  <button data-target="studentsContent"><i class="fa-solid fa-users"></i> Students</button>

  <?php if ($role === 'super'): ?>
    <button data-target="manageAdminContent"><i class="fa-solid fa-user-gear"></i> Manage Admin</button>
  <?php endif; ?>

</nav>

<!-- Main Dashboard -->
<main class="main">

  <!-- DASHBOARD CONTENT -->
  <div id="dashboardContent" class="contentPanel">
    <?php include("dashboard.php"); ?>
  </div>

  <!-- PENDING APPROVAL CONTENT -->
  <div id="approvalContent" class="contentPanel" style="display:none;">
    <?php include("pendingapproval.php"); ?>
  </div>

  <!-- COMPANIES CONTENT -->
  <div id="companiesContent" class="contentPanel" style="display:none;">
    <?php include("companies.php"); ?>
  </div>

  <!-- DEPARTMENTS CONTENT -->
  <div id="departmentsContent" class="contentPanel" style="display:none;">
    <?php include("college.php"); ?>
  </div>

  <!-- STUDENTS CONTENT -->
  <div id="studentsContent" class="contentPanel" style="display:none;">
    <?php include("student.php"); ?>
  </div>

  <!-- MANAGE ADMIN CONTENT -->
  <?php if ($role === 'super'): ?>
    <div id="manageAdminContent" class="contentPanel" style="display:none;">
      <?php include("manageAdmin.php"); ?>
    </div>
  <?php endif; ?>

  
</main>

<!-- Robust JS -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".menu-bar button");
  const panels = document.querySelectorAll(".contentPanel");

  function showPanel(id) {
    panels.forEach(p => p.style.display = "none");
    const target = document.getElementById(id);
    if (target) target.style.display = "block";
  }

  function setActive(targetId) {
    buttons.forEach(b => {
      if (b.dataset.target === targetId) {
        b.classList.add("active");
      } else {
        b.classList.remove("active");
      }
    });
  }

  // Expose globally so dashboard.php can call them
  window.showPanel = showPanel;
  window.setActive = setActive;

  // Menu bar click handling
  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      buttons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      const targetId = btn.dataset.target;
      if (targetId) showPanel(targetId);
    });
  });

  // Initial state
  const initial = document.querySelector(".menu-bar button.active");
  const initialTarget = initial?.dataset.target || "dashboardContent";
  showPanel(initialTarget);

  // Quick Action buttons (inside dashboard.php)
  const reviewBtn = document.getElementById("reviewPostsBtn");
  if (reviewBtn) {
    reviewBtn.addEventListener("click", () => {
      showPanel("approvalContent");
      setActive("approvalContent");
    });
  }

  const collegeBtn = document.getElementById("viewCollegeBtn");
  if (collegeBtn) {
    collegeBtn.addEventListener("click", () => {
      showPanel("departmentsContent");
      setActive("departmentsContent");
    });
  }

  const companiesBtn = document.getElementById("viewCompaniesBtn");
  if (companiesBtn) {
    companiesBtn.addEventListener("click", () => {
      showPanel("companiesContent");
      setActive("companiesContent");
    });
  }
});
</script>

</body>
</html>