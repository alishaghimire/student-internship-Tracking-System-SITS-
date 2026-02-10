<?php
require_once "collegedb_connection.php";
session_start();

$college_id = $_SESSION['college_id']; // logged-in college
$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

// --- Debug line (optional) ---
// echo "Company ID: $company_id, College ID: $college_id";

// --- Fetch Company Details ---
$company_sql = "SELECT * FROM company_registration WHERE company_id = ?";
$stmt = $conn->prepare($company_sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();

// --- Fetch Interns for this Company & College ---
$intern_sql = "
    SELECT s.student_id, s.full_name, s.student_code, s.email, s.phone, s.major,
           s.college_name,
           a2.experience_level, a2.university, a2.graduation_year,
           a1.dob, a3.resume_path, a3.portfolio_url
    FROM applications app
    JOIN students_registrtaion s ON app.student_id = s.student_id
    JOIN application_step1 a1 ON app.application_id = a1.application_id
    JOIN application_step2 a2 ON app.application_id = a2.application_id
    JOIN application_step3 a3 ON app.application_id = a3.application_id
    WHERE app.company_id = ? AND s.college_id = ?
";
$stmt2 = $conn->prepare($intern_sql);
$stmt2->bind_param("ii", $company_id, $college_id);
$stmt2->execute();
$interns = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Details</title>
  <link rel="stylesheet" href="studentDetailsinsidecompany.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="container">

  <!-- Header -->
  <header>
    <a href="companies.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Companies</a>
    <h1>Company Details</h1>
    <p class="subtitle">View company information and active interns</p>
  </header>

  <!-- Company Info -->
  <?php if($company): ?>
  <section class="company-info">
    <h2><i class="fas fa-building"></i> <?= htmlspecialchars($company['company_name']) ?></h2>
    <ul>
      <li><i class="fas fa-industry"></i><strong>Industry:</strong> <?= htmlspecialchars($company['industry']) ?></li>
      <li><i class="fas fa-map-marker-alt"></i><strong>Location:</strong> <?= htmlspecialchars($company['city_state']) ?></li>
      <li><i class="fas fa-calendar-alt"></i><strong>Founded:</strong> <?= htmlspecialchars($company['established_year']) ?></li>
      <li><i class="fas fa-users"></i><strong>Employees:</strong> <?= htmlspecialchars($company['num_employees']) ?></li>
    </ul>
  </section>

  <!-- Contact -->
  <section class="contact">
    <h3><i class="fas fa-address-book"></i> Contact Information</h3>
    <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($company['email']) ?></p>
    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($company['phone']) ?></p>
    <p><i class="fas fa-globe"></i> <?= htmlspecialchars($company['website']) ?></p>
  </section>
  <?php else: ?>
    <p>No company found with this ID.</p>
  <?php endif; ?>

  <!-- Interns -->
  <section class="interns">
    <h3><i class="fas fa-user-graduate"></i> Currently Active Interns</h3>
    <div class="interns-grid">
      <?php if($interns->num_rows > 0): ?>
        <?php while($intern = $interns->fetch_assoc()): ?>
          <div class="intern-card">
            <h4><i class="fas fa-user"></i> <?= htmlspecialchars($intern['full_name']) ?></h4>
            <p><i class="fas fa-id-card"></i> ID: <?= htmlspecialchars($intern['student_code']) ?></p>
            <p><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($intern['experience_level']) ?> Intern</p>
            <p><i class="fas fa-book"></i> <?= htmlspecialchars($intern['major']) ?></p>
            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($intern['email']) ?></p>
            <p><i class="fas fa-phone"></i> <?= htmlspecialchars($intern['phone']) ?></p>
            <p><i class="fas fa-university"></i> <?= htmlspecialchars($intern['university']) ?> (<?= htmlspecialchars($intern['graduation_year']) ?>)</p>
            <p><i class="fas fa-school"></i> College: <?= htmlspecialchars($intern['college_name']) ?></p>
            <span class="status active"><i class="fas fa-check-circle"></i> Active</span>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No students from your college have applied to this company yet.</p>
      <?php endif; ?>
    </div>
  </section>

</div>
</body>
</html>