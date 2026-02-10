<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Company Dashboard</title>
  <link rel="stylesheet" href="/project/css/Company/companydashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>

  <!-- Sidebar -->
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

    <!-- Top Bar -->
    <header class="topbar">
      <h1 class="page-title">Company Dashboard</h1>
      <div class="topbar-actions" id="dashboardTopbar"></div>
    </header>

    <!-- DASHBOARD CONTENT -->
    <div id="dashboardContent" class="contentPanel">
      <?php include(__DIR__ . "/dashboard.php"); ?>
    </div>

    <!-- APPLICATION CONTENT -->
    <div id="applicationContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "/application.php"); ?>
    </div>

    <!-- STUDENT CONTENT -->
    <div id="studentContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "/student.php"); ?>
    </div>

    <!-- INTERVIEW CONTENT -->
    <div id="interviewContent" class="contentPanel" style="display:none;">
    <?php include(__DIR__ . "/interview.php"); ?>
    </div>

    <!-- MESSAGE CONTENT -->
    <div id="messageContent" class="contentPanel" style="display:none;">
      <h2>Message Content</h2>
    </div>

    <!-- REPORT CONTENT -->
    <div id="reportContent" class="contentPanel" style="display:none;">
      <h2>Report Content</h2>
    </div>

    <!-- INTERNSHIP CONTENT (FIXED: added class="contentPanel") -->
    <div id="internshipContent" class="contentPanel" style="display:none;">
      <?php include(__DIR__ . "/internship.php"); ?>
    </div>

    <!-- SETTINGS CONTENT (FIXED: added class="contentPanel") -->
    <div id="settingsContent" class="contentPanel" style="display:none;">
      <h2>Settings Content</h2>
    </div>

  </main>

  <!-- POST POSITION MODAL -->
  <div id="postPositionModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
      <button id="closeModalBtn" class="close-btn">×</button>
      <div id="modalBody"></div>
    </div>
  </div>

  <!-- JS -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {

      function showSection(sectionId) {
        document.querySelectorAll(".contentPanel").forEach(div => {
          div.style.display = "none";
        });
        document.getElementById(sectionId).style.display = "block";
      }

      // Sidebar navigation
      document.getElementById("dashboardNav").onclick = () => showSection("dashboardContent");
      document.getElementById("applicationNav").onclick = () => showSection("applicationContent");
      document.getElementById("studentNav").onclick = () => showSection("studentContent");
      document.getElementById("interviewNav").onclick = () => showSection("interviewContent");
      document.getElementById("messageNav").onclick = () => showSection("messageContent");
      document.getElementById("reportNav").onclick = () => showSection("reportContent");
      document.getElementById("internshipNav").onclick = () => showSection("internshipContent");
      document.getElementById("settingsNav").onclick = () => showSection("settingsContent");

      // Default section
      showSection("dashboardContent");

      /* Modal handling */
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