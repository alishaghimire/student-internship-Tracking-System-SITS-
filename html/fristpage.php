<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internship Tracking System</title>

  <!-- Inline CSS -->
  <link rel="stylesheet" href="/project/css/firstpage.css">
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <div class="container">
    <h1>Student Internship Tracking System</h1>
    <p class="tagline">Streamline your internship management process with our comprehensive platform.</p>

    <div class="cards">
      <!-- Admin Card -->
      <div class="card">
        <i class="fas fa-building"></i>
        <h2>Admin</h2>
        <p>Manage student internships and coordinate with companies</p>
        <button id="Adminbtn"><a href="/project/html/Admin/AdminLogin.php" class="admin-btn">Continue →</a></button>
      </div>

      <!-- Company Card -->
      <div class="card">
        <i class="fas fa-user-tie"></i>
        <h2>Company</h2>
        <p>Post internship opportunities and manage applications</p>
        <button><a href="/project/html/Company/companylogin.php" class="company-btn">Continue →</a></button>
      </div>

      <!-- Student Card -->
      <div class="card">
        <i class="fas fa-graduation-cap"></i>
        <h2>Student</h2>
        <p>Find and apply for internship opportunities</p>
        <button><a href="/project/html/Student/studentLogin.php" class="student-btn">Continue →</a></button>
      </div>

      <!-- College Card -->
      <div class="card">
        <i class="fas fa-university"></i>
        <h2>College</h2>
        <p>Register your college</p>
        <button><a href="/project/html/College/Login.php" class="college-btn">Continue →</a></button>
      </div>
    </div>
  </div>
</body>
</html>