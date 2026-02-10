<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'dbconnectcompany.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}

$company_id = (int) $_SESSION['company_id'];

// Active Interns: shortlisted interns for this company
$stmt = $conn->prepare("
    SELECT COUNT(*) AS cnt
    FROM applications a
    INNER JOIN postposition p ON a.post_id = p.id
    WHERE p.company_id = ? AND a.status = 'shortlisted'
");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$activeInterns = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// Open Positions: total posts by this company (no approval filter)
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM postposition WHERE company_id = ?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$openPositions = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// Pending Applications: applications not shortlisted yet
$stmt = $conn->prepare("
    SELECT COUNT(*) AS cnt
    FROM applications a
    INNER JOIN postposition p ON a.post_id = p.id
    WHERE p.company_id = ? AND (a.status IS NULL OR a.status != 'shortlisted')
");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$pendingApplications = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// Fetch latest 3 internship positions with applicant count (no approval filter)
$stmt = $conn->prepare("
  SELECT 
    p.id, 
    p.title, 
    p.department, 
    p.location, 
    p.created_at, 
    p.deadline,
    COUNT(a.application_id) AS applicant_count
  FROM postposition p
  LEFT JOIN applications a ON a.post_id = p.id
  WHERE p.company_id = ?
  GROUP BY p.id, p.title, p.department, p.location, p.created_at, p.deadline
  ORDER BY p.id DESC
  LIMIT 3
");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

function formatDate($date) {
  return date("M d, Y", strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Company Dashboard</title>

  <link rel="stylesheet" href="../../css/Company/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <div class="container">
    <!-- TOPBAR -->
    <header class="topbar">
      <h1>Company Dashboard</h1>
    </header>

    <!-- METRICS -->
    <section class="metrics">
      <div class="metric-card blue">
        <div class="icon"><i class="fas fa-user-graduate"></i></div>
        <div class="label">Active Interns</div>
        <div class="value"><?php echo $activeInterns; ?></div>
      </div>

      <div class="metric-card green">
        <div class="icon"><i class="fas fa-briefcase"></i></div>
        <div class="label">Open Positions</div>
        <div class="value"><?php echo $openPositions; ?></div>
      </div>

      <div class="metric-card orange">
        <div class="icon"><i class="fas fa-file-alt"></i></div>
        <div class="label">Pending Applications</div>
        <div class="value"><?php echo $pendingApplications; ?></div>
      </div>
    </section>
  
    <!-- INTERNSHIP POSITIONS -->
    <section class="content">
      <div class="positions">
        <h2>Internship Positions</h2>

        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
              <div class="info">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <p><?php echo htmlspecialchars($row['department']); ?> • <?php echo htmlspecialchars($row['location']); ?></p>
                <p>Applicants: <?php echo (int)$row['applicant_count']; ?></p>
                <p>
                  Posted: <?php echo formatDate($row['created_at']); ?> • 
                  Deadline: <?php echo formatDate($row['deadline']); ?>
                </p>
              </div>
              <div class="actions">
                <button class="btn" onclick="window.location.href='viewDetailsposition.php?id=<?php echo $row['id']; ?>'">
                  View Details
                </button>
                <button class="btn outline" onclick="window.location.href='applications.php?internship_id=<?php echo $row['id']; ?>'">
                  Applications
                </button>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No internship positions found.</p>
        <?php endif; ?>

        <!-- Show More Button -->
        <div style="text-align:center; margin-top:20px;">
          <button class="btn outline" onclick="showSection('internshipContent','internshipNav')">
            Show More
          </button>
        </div>
      </div>

      <!-- QUICK ACTIONS -->
      <aside class="quick-actions">
        <p class="subtitle">Quick Action</p>
        <ul>
          <li>
            <a href="postposition.php">
              <button class="action-btn blue"><i class="fas fa-briefcase"></i> Post Position</button>
            </a>
          </li>
         <button class="action-btn green" onclick="showSection('applicationContent', 'applicationNav')">
  <i class="fas fa-check-circle"></i> Review Applications
</button>

<button class="action-btn purple" onclick="showSection('interviewContent', 'interviewNav')">
  <i class="fas fa-calendar-alt"></i> Schedule Interview
</button>
<button class="action-btn gray" onclick="showSection('settingsContent', 'settingsNav')">
  <i class="fas fa-cog"></i> Manage Settings
</button>        </ul>
      </aside>
    </section>
  </div>

  <!-- JS -->
  <script>
function showSection(id, navId) {
  document.querySelectorAll(".contentPanel").forEach(div => div.style.display = "none");
  document.getElementById(id).style.display = "block";

  document.querySelectorAll(".nav-link").forEach(link => link.classList.remove("active"));
  document.getElementById(navId).classList.add("active");
  
  document.querySelectorAll(".contentPanel").forEach(div => div.style.display = "none");
  document.getElementById(id).style.display = "block";

  document.querySelectorAll(".nav-link").forEach(link => link.classList.remove("active"));
  document.getElementById(navId).classList.add("active");

}

document.addEventListener("DOMContentLoaded", () => {
  // Attach sidebar handlers
  dashboardNav.onclick   = () => showSection("dashboardContent", "dashboardNav");
  applicationNav.onclick = () => showSection("applicationContent", "applicationNav");
  studentNav.onclick     = () => showSection("studentContent", "studentNav");
  interviewNav.onclick   = () => showSection("interviewContent", "interviewNav");
  internshipNav.onclick  = () => showSection("internshipContent", "internshipNav");
  settingsNav.onclick    = () => showSection("settingsContent", "settingsNav");

  showSection("dashboardContent", "dashboardNav");

  // Modal logic...
});
</script>
</body>
</html>