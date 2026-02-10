<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Portal</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #f4f7fc;
      color: #333;
      min-height: 100vh;
      display: flex;
    }

    .dashboard {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    .sidebar {
      width: 250px;
      background: #ffffff;
      border-right: 1px solid #ddd;
      padding: 20px;
      box-shadow: 2px 0 8px rgba(0,0,0,0.05);
    }

    .sidebar .user-info h2 {
      font-size: 1.4rem;
      color: #4c4cff;
      margin-bottom: 20px;
      text-align: center;
    }

    .sidebar nav {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      background: #f5f7fa;
      border-left: 5px solid transparent;
      border-radius: 8px;
      color: #333;
      text-decoration: none;
      font-size: 0.95rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .nav-link:hover {
      background: #e0e7ff;
      border-left: 5px solid #4c4cff;
    }

    .nav-link.active {
      background: #e6f0ff;
      border-left: 5px solid #4c4cff;
      color: #4c4cff;
      font-weight: 600;
    }

    .profile-submenu {
      list-style: none;
      padding-left: 20px;
      margin-top: 5px;
      display: none;
    }

    .profile-submenu .nav-link {
      font-size: 0.9rem;
      padding: 10px 14px;
      background: #f0f4ff;
      border-radius: 6px;
      color: #4c4cff;
      box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 20px;
    }

    .topbar {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 12px 20px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .btnlogout {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #ff4c4c;
      color: #fff;
      padding: 10px 18px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 600;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .btnlogout:hover {
      background: #e63939;
      transform: translateY(-2px);
    }

    .btnlogout i {
      font-size: 1rem;
    }

    .contentPanel iframe {
      width: 100%;
      height: 900px;
      border: none;
      border-radius: 10px;
      background: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
  </style>
</head>
<body>
  <div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="user-info">
        <h2>Student Dashboard</h2>
      </div>

      <nav>
        <a class="nav-link active" id="overviewNav"><i class="fas fa-home"></i> Overview</a>
        <a class="nav-link" id="internshipNav"><i class="fas fa-search"></i> Browse Internships</a>
        <a class="nav-link" id="applicationsNav"><i class="fas fa-file-alt"></i> My Applications</a>
        <a class="nav-link" id="logbookNav"><i class="fas fa-file-alt"></i> My LogBook</a>

        <a class="nav-link" id="profileToggle"><i class="fas fa-user"></i> Profile ▼</a>

        <ul id="profileMenu" class="profile-submenu">
          <li><a class="nav-link" data-profile="personal">Personal Detail</a></li>
          <li><a class="nav-link" data-profile="contractdetails">Contact Detail</a></li>
          <li><a class="nav-link" data-profile="education">Education</a></li>
          <li><a class="nav-link" data-profile="preview">Preview</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main Section -->
    <main class="main">
      <header class="topbar">
        <a href="logout.php" class="btnlogout">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </header>

      <!-- Dynamic Content -->
      <div id="mainContentArea" class="contentPanel">
        <iframe src="overview.php"></iframe>
      </div>
    </main>
  </div>

  <!-- JS -->
  <script>
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');

    profileToggle.addEventListener('click', (e) => {
      e.preventDefault();
      profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
    });

    const navMap = {
      overviewNav: "overview.php",
      internshipNav: "BrowseInternship.php",
      applicationsNav: "myApplicationOffers.php",
      logbookNav: "mylogbook.php",
    };

    Object.keys(navMap).forEach(navId => {
      const navLink = document.getElementById(navId);
      navLink.addEventListener("click", e => {
        e.preventDefault();

        document.querySelectorAll(".sidebar nav > .nav-link").forEach(link => link.classList.remove("active"));
        navLink.classList.add("active");

        profileMenu.style.display = "none";

        document.getElementById("mainContentArea").innerHTML =
          `<iframe src="${navMap[navId]}"></iframe>`;
      });
    });

    document.querySelectorAll('#profileMenu .nav-link').forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();

        document.querySelectorAll(".sidebar nav > .nav-link").forEach(l => l.classList.remove("active"));
        profileToggle.classList.add("active");

        const page = link.getAttribute('data-profile') + ".php";

        document.getElementById("mainContentArea").innerHTML =
          `<iframe src="${page}" style="height:900px;"></iframe>`;
      });
    });

    // Allow overview.php cards to trigger Browse Internship panel
    function loadBrowseInternship(id) {
      document.querySelectorAll(".sidebar nav > .nav-link").forEach(link => link.classList.remove("active"));
      document.getElementById("internshipNav").classList.add("active");

      document.getElementById("mainContentArea").innerHTML =
        `<iframe src="BrowseInternship.php?id=${id}"></iframe>`;
    }
  </script>
</body>
</html>