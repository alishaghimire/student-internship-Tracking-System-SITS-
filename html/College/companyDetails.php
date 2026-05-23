<?php
require_once "collegedb_connection.php";
session_start();
$college_id = $_SESSION['college_id']; // logged-in college

// Fetch all approved companies
$sql = "SELECT company_id, company_name, industry, city_state, num_employees 
        FROM company_registration 
        WHERE status='approved'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Companies</title>
  <link rel="stylesheet" href="companyDetails.css">
  <style>
    .back-btn {
      display:inline-block;
      margin-bottom:15px;
      padding:8px 14px;
      background:#e5e7eb;
      border-radius:6px;
      text-decoration:none;
      color:#333;
      font-weight:600;
    }
    .back-btn:hover {
      background:#d1d5db;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Approved Companies</h1>

  <!-- Back button -->
  <a href="http://localhost/project/html/College/collage.php" class="back-btn">← Back</a>

  <div class="company-grid">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="company-card">
        <h2><?= htmlspecialchars($row['company_name']) ?></h2>
        <p><strong>Industry:</strong> <?= htmlspecialchars($row['industry']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($row['city_state']) ?></p>
        <p><strong>Employees:</strong> <?= htmlspecialchars($row['num_employees']) ?></p>
        <a href="studentDetailsinsidecompany.php?company_id=<?= $row['company_id'] ?>" class="details-btn">
          View Details
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>