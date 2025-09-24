<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internship Tracking System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Top Navbar -->
  <header class="navbar">
    <div class="logo">
      🎓 <span>Internship Tracking System</span>
      <small>Administrator Dashboard</small>
    </div>
    <div class="profile">AU</div>
  </header>

  <!-- Dashboard Section -->
  <main class="dashboard">
    <h1>Admin Dashboard</h1>
    <p class="subtitle">Manage the entire internship tracking system</p>

    <!-- Dashboard Cards -->
    <div class="cards">
      <div class="card">
        <p>Total Students</p>
        <h2></h2>
      </div>
      <div class="card">
        <p>Companies</p>
        <h2></h2>
      </div>
      <div class="card">
        <p>Internships</p>
        <h2></h2>
      </div>
      <div class="card">
        <p>Applications</p>
        <h2></h2>
      </div>
      <div class="card">
        <p>Accepted</p>
        <h2></h2>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <button class="active">Students</button>
      <button>Companies</button>
      <button>Internships</button>
      <button>Applications</button>
      <button>Logbooks</button>
    </div>

    <!-- Search & Add -->
    <div class="toolbar">
      <input type="text" placeholder="Search students...">
      <button class="add-btn">+ Add Student</button>
    </div>

    <!-- Students List -->
    <section class="student-list">
      <h3>Students </h3>
      <!-- Student items will go here -->
    </section>
  </main>

  <!-- Logout Button -->
  <button class="logout">Logout</button>
</body>
</html>
