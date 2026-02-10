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
</head>
<body>
<div class="container">
  <h1>Approved Companies</h1>
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