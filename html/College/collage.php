<?php
session_start();
require_once "collegedb_connection.php";

// Example: fetch the logged-in college from database
$sql = "SELECT * FROM colleges WHERE authorized_email= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['college_email']); // or whatever you stored at login
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $_SESSION['college_id'] = $row['college_id'];
    $_SESSION['college_name'] = $row['college_name'];
} else {
    echo "No college found in session.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Portal</title>
    <link rel="stylesheet" href="collage.css">
    <style>
        /* =========================
   GOOGLE FONT
========================= */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* =========================
   RESET
========================= */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

/* =========================
   ROOT COLORS
========================= */
:root {
  --primary: #4f46e5;
  --secondary: #10b981;
  --bg: #f8fafc;
  --card-bg: rgba(255, 255, 255, 0.9);
  --text-dark: #1e293b;
  --text-light: #64748b;
  --shadow: 0 15px 35px rgba(0,0,0,0.08);
}

/* =========================
   BODY
========================= */
body {
  background: linear-gradient(135deg, #eef2ff, #f0fdf4);
  min-height: 100vh;
  color: var(--text-dark);
}

/* =========================
   NAVBAR
========================= */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 40px;
  background: rgba(255,255,255,0.8);
  backdrop-filter: blur(12px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  position: sticky;
  top: 0;
  z-index: 100;
}

/* Logo */
.logo {
  display: flex;
  align-items: center;
  gap: 15px;
}

.logo-icon {
  width: 55px;
  height: 55px;
  object-fit: contain;
}

.logo h2 {
  font-size: 1.4rem;
  font-weight: 600;
}

.logo p {
  font-size: 0.85rem;
  color: var(--text-light);
}

/* Logout Button */
.logout-btn {
  padding: 10px 26px;
  border: none;
  border-radius: 30px;
  font-size: 0.95rem;
  font-weight: 500;
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: #fff;
  cursor: pointer;
  transition: 0.3s ease;
  box-shadow: 0 8px 20px rgba(239,68,68,0.35);
}

.logout-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 25px rgba(239,68,68,0.45);
}

/* =========================
   HERO SECTION
========================= */
.hero {
  text-align: center;
  padding: 60px 20px 40px;
}

.hero h1 {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  color: transparent;
  margin-bottom: 10px;
}

.hero p {
  font-size: 1.05rem;
  color: var(--text-light);
}

/* =========================
   CARDS SECTION
========================= */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  max-width: 900px;
  margin: 40px auto;
  padding: 0 20px 60px;
}

/* =========================
   CARD
========================= */
.card {
  background: var(--card-bg);
  border-radius: 18px;
  padding: 35px 30px;
  box-shadow: var(--shadow);
  cursor: pointer;
  transition: all 0.35s ease;
  position: relative;
  overflow: hidden;
}

.card::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
    120deg,
    rgba(79,70,229,0.12),
    rgba(16,185,129,0.12)
  );
  opacity: 0;
  transition: 0.3s;
}

.card:hover::before {
  opacity: 1;
}

.card:hover {
  transform: translateY(-12px) scale(1.02);
}

/* Icon */
.icon {
  font-size: 3rem;
  width: 80px;
  height: 80px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  background: rgba(79,70,229,0.12);
}

/* Different icon colors */
.user-icon {
  background: rgba(79,70,229,0.15);
}

.log-icon {
  background: rgba(16,185,129,0.15);
}

/* Card Text */
.card h3 {
  font-size: 1.4rem;
  margin-bottom: 10px;
  font-weight: 600;
}

.card p {
  font-size: 0.95rem;
  color: var(--text-light);
  line-height: 1.5;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    gap: 15px;
  }

  .hero h1 {
    font-size: 2rem;
  }
}

    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <img src="collage.png" class="logo-icon">
            <div>
                <h2>College Dashboard</h2>
                <p>Student Internship Tracking System</p>
            </div>
        </div>
        

        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <section class="hero">
        <h1>Welcome to College Dashboard</h1>
        <p>Select a section to view or manage your information</p>
    </section>

    <section class="cards">

        <div class="card" onclick="openPage('studentdetails.php')">
            <div class="icon user-icon"></div>
            <h3>Student Details</h3>
            <p>View and edit your personal information, contact details, and academic status</p>
        </div>

        <div class="card" onclick="openPage('CStudentLogbook.php')">
            <div class="icon log-icon"></div>
            <h3>Student Log Book</h3>
            <p>Track your activities, assignments, and projects with detailed logs</p>
        </div>
        
<div class="card" onclick="openPage('companyDetails.php?company_id=10')">            <div class="icon log-icon"></div>
            <h3>Company Deatails</h3>
            <p>Track your activities, assignments, and projects with detailed logs</p>
        </div>

    </section>

    <script src="collage.js"></script>
</body>
</html>
