<?php
// =======================
// DB CONNECTION + SESSION
// =======================
session_start();
require_once "dbconnectcompany.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}

$company_id = $_SESSION['company_id'];

// =======================
// FETCH COMPANY INFO
// =======================
$sqlCompany = "SELECT company_name, industry, city_state, email
               FROM company_registration
               WHERE company_id = ?";
$stmt = $conn->prepare($sqlCompany);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_registration = $stmt->get_result()->fetch_assoc();
$stmt->close();

$department = $company_registration['company_name'] ?? 'ABC Int';
$industry   = $company_registration['industry'] ?? 'Tech';
$area       = $company_registration['city_state'] ?? 'Kapan';
$email      = $company_registration['email'] ?? 'N/A';

// =======================
// FETCH APPLICATION DATA
// =======================
$sql = "
SELECT 
    sr.full_name,
    sr.email,
    sr.phone,
    s2.education_level,
    s2.university,
    s2.graduation_year,
    s2.experience_level,
    s2.availability,
    s2.cover_letter,
    a.application_id
FROM applications a
JOIN students_registrtaion sr ON a.student_id = sr.student_id
JOIN application_step2 s2 ON a.application_id = s2.application_id
WHERE a.company_id = ?
ORDER BY a.application_id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// =======================
// STATS
// =======================
$stmtTotal = $conn->prepare("
SELECT COUNT(*) AS total 
FROM applications 
WHERE company_id = ?
");
$stmtTotal->bind_param("i", $company_id);
$stmtTotal->execute();
$total = $stmtTotal->get_result()->fetch_assoc()['total'];
$stmtTotal->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Application Dashboard • <?= htmlspecialchars($department) ?></title>
<link rel="stylesheet" href="applications.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
<a href="CDashboard.php" style="display:block;margin:10px 0;">Back</a>

<main class="main-content">

  <!-- ================= HEADER ================= -->
  <div class="top-header">
    <div class="page-title">
      <h1><i class="fas fa-users"></i> Application Dashboard</h1>
      <p>
        Admin • <?= htmlspecialchars($department) ?> • 
        <?= htmlspecialchars($industry) ?><?= $area ? ', ' . htmlspecialchars($area) : '' ?>
      </p>
    </div>

    <div class="user-profile">
      <div class="user-avatar">AJ</div>
      <div>
        <div style="font-weight:600;"><?= htmlspecialchars($department) ?></div>
        <div style="font-size:13px;color:gray;"><?= htmlspecialchars($email) ?></div>
      </div>
    </div>
  </div>

  <!-- ================= STATS ================= -->
  <div class="stats-cards">
    <div class="stat-card">
      <div class="stat-icon total"><i class="fas fa-file-alt"></i></div>
      <div class="stat-info">
        <h3>Total Applications</h3>
        <div class="number"><?= $total ?></div>
      </div>
    </div>
  </div>

  <!-- ================= FILTER UI (STATIC) ================= -->
  <div class="filters-bar">
    <input type="text" placeholder="Search (static UI)">
    <select>
      <option>All Statuses</option>
      <option>Pending</option>
      <option>Reviewed</option>
    </select>
    <input type="date">
  </div>

  <!-- ================= APPLICATIONS ================= -->
  <div class="applicants-grid">

  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
    <div class="applicant-card">
      <div class="applicant-header">
        <div class="applicant-info">
          <h3><?= htmlspecialchars($row['full_name']) ?></h3>
          <p>Education Level: <?= htmlspecialchars($row['education_level']) ?></p>
        </div>
        <div class="status-badge status-pending">Pending</div>
      </div>

      <div class="applicant-details">
        <div class="detail-item"><i class="fas fa-envelope"></i> <span><?= htmlspecialchars($row['email']) ?></span></div>
        <div class="detail-item"><i class="fas fa-phone"></i> <span><?= htmlspecialchars($row['phone']) ?></span></div>
        <div class="detail-item"><i class="fas fa-university"></i> <span><?= htmlspecialchars($row['university']) ?></span></div>
        <div class="detail-item"><i class="fas fa-graduation-cap"></i> <span>Graduation Year: <?= htmlspecialchars($row['graduation_year']) ?></span></div>
        <div class="detail-item"><i class="fas fa-briefcase"></i> <span><?= htmlspecialchars($row['experience_level']) ?></span></div>
        <div class="detail-item"><i class="fas fa-clock"></i> <span><?= htmlspecialchars($row['availability']) ?></span></div>
      </div>

      <div class="cover-letter">
        <?= nl2br(htmlspecialchars($row['cover_letter'])) ?>
      </div>

      <div class="card-actions">
        <button class="btn btn-view" onclick="window.open('viewDetails2.php?application_id=<?= $row['application_id'] ?>','_blank')">
          <i class="fas fa-eye"></i> View Full
        </button>
      </div>
    </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No applications found for your company.</p>
  <?php endif; ?>

  </div>

</main>
</body>
</html>