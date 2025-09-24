<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Internship Dashboard</title>
  <link rel="stylesheet" href="../css/proj/studentdashboard.css" />
  <style>
    #internship-section {
      display: none;
      background-color: #f4f4f4;
      padding: 20px;
      margin: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
  <header>
    <img src="../pictures/graduation.png" alt="">
    <div class="top">
      <h1>Internship Tracking System</h1>
      <h2>Student Dashboard</h2>
    </div>
    <a href="#"><div class="profile">AU</div></a>
  </header>

  <section class="welcome">
    <p>Welcome back, <strong>Ram</strong>!</p>
  </section>

  <div class="nav-wapper">
    <nav class="dashboard-nav">
      <ul>
        <li><a href="#" class="active" id="ref">My Internships</a></li>
        <li><a href="#" class="active">Apply for Internships</a></li>
        <li><a href="#" class="active">My Logbook</a></li>
        <li><a href="#" class="active">My CV</a></li>
      </ul>
    </nav>
  </div>

  <!-- 👇 New internship section that appears on click -->
  <div id="internship-section">
    <h3>My Internship Details</h3>
    <p>This section displays your current internship status, applications, and progress.</p>
    <!-- You can add dynamic PHP or AJAX content here -->
  </div>

  <main>
    <div class="fetch-data">
      <p>No internship data available yet. Please check back soon.</p>
    </div>

    <div class="fetch-data">
      <p>Latest updates will appear here once available.</p>
    </div>
  </main>

  <div class="logout">
    <img src="../pictures/logout.png" alt="photo"><a href="login.php">Logout</a>
  </div>

  <script>
    document.getElementById("ref").addEventListener("click", function(e) {
      e.preventDefault();
      const section = document.getElementById("internship-section");
      section.style.display = section.style.display === "none" ? "block" : "none";
    });
  </script>
</body>
</html>