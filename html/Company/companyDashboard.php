<?php
session_start();
if (!isset($_SESSION['company_id'])) {
    echo "<p style='color:red; padding:10px;'>Unauthorized access.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Company Dashboard</title>

  <!-- CSS -->
  <link rel="stylesheet" href="/project/css/Company/companydashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <span class="brand-logo"><i class="fas fa-building"></i></span>
      <span class="brand-name">Company</span>
    </div>

    <nav class="nav">
      <a class="nav-link active" id="dashboardNav">Dashboard</a>
      <a class="nav-link" id="applicationNav">Application</a>
      <a class="nav-link" id="studentNav">Student</a>
      <a class="nav-link" id="interviewNav">Interviews</a>
      <a class="nav-link" id="messageNav">Message</a>
      <a class="nav-link" id="reportNav">Reports</a>
      <a class="nav-link" id="internshipNav">Internship</a>
      <a class="nav-link" id="settingsNav">Settings</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main">

    <!-- TOPBAR -->
    <header class="topbar">
      <div class="topbar-actions"></div>
    </header>

    <!-- DASHBOARD -->
    <div id="dashboardContent" class="contentPanel">
      <?php include(__DIR__ . "/dashboard.php"); ?>
    </div>

    <!-- APPLICATION -->
    <div id="applicationContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "/application.php"); ?>
    </div>

    <!-- STUDENT -->
    <div id="studentContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "/student.php"); ?>
    </div>

    <!-- INTERVIEW -->
    <div id="interviewContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "/internship.php"); ?>
    </div>

    <!-- MESSAGE -->
    <div id="messageContent" class="contentPanel" style="display:none;">
      <h2>Message Content</h2>
    </div>

    <!-- REPORT -->
    <div id="reportContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "//interview.php"); ?>
    </div>

    <!-- INTERNSHIP -->
    <div id="internshipContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "/internship.php"); ?>
    </div>

    <!-- SETTINGS -->
    <div id="settingsContent" class="contentPanel" style="display:none;">
      <h2>Settings Content</h2>
    </div>

  </main>

  <!-- MODAL -->
  <div id="postPositionModal" class="modal-overlay">
    <div class="modal-content">
      <button id="closeModalBtn" class="close-btn">×</button>
      <div id="modalBody"></div>
    </div>
  </div>

  <!-- JS -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {

      function showSection(id) {
        document.querySelectorAll(".contentPanel").forEach(div => div.style.display = "none");
        document.getElementById(id).style.display = "block";
      }

      dashboardNav.onclick = () => showSection("dashboardContent");
      applicationNav.onclick = () => showSection("applicationContent");
      studentNav.onclick = () => showSection("studentContent");
      interviewNav.onclick = () => showSection("interviewContent");
      messageNav.onclick = () => showSection("messageContent");
      reportNav.onclick = () => showSection("reportContent");
      internshipNav.onclick = () => showSection("internshipContent");
      settingsNav.onclick = () => showSection("settingsContent");

      showSection("dashboardContent");

      const modal = document.getElementById("postPositionModal");
      const modalBody = document.getElementById("modalBody");
      const closeBtn = document.getElementById("closeModalBtn");

      document.querySelectorAll(".post-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
          const res = await fetch("postposition.php");
          modalBody.innerHTML = await res.text();
          modal.style.display = "flex";
        });
      });

      closeBtn.onclick = () => modal.style.display = "none";
      window.onclick = e => { if (e.target === modal) modal.style.display = "none"; };
    });
  </script>

</body>
</html>