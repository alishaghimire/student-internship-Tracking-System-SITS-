<?php
require_once "studentdbconnection.php";
session_start();

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    die("Unauthorized access");
}

$student_id = $_SESSION['student_id'];

// Fetch student name
$studentQuery = $conn->prepare("SELECT full_name FROM students_registrtaion WHERE student_id = ?");
$studentQuery->bind_param("i", $student_id);
$studentQuery->execute();
$studentResult = $studentQuery->get_result();
$student = $studentResult->fetch_assoc();
$student_name = $student ? $student['full_name'] : "Student";

// ======================
// Summary stats
// ======================
$stats = [
    'applications' => 0,
    'review' => 0,
    'interview' => 0,
    'accepted' => 0
];

$sqlStats = "SELECT status FROM applications WHERE student_id=?";
$stmt = $conn->prepare($sqlStats);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$resultStats = $stmt->get_result();

while ($row = $resultStats->fetch_assoc()) {
    $stats['applications']++;

    $status = strtolower($row['status']);
    switch ($status) {
        case 'reviewed':
            $stats['review']++;
            break;
        case 'shortlisted':
            $stats['accepted']++; // treat shortlisted as accepted
            break;
        case 'interview':
            $stats['interview']++;
            break;
    }
}

// Count interviews for this student
$sqlInterview = "
    SELECT COUNT(*) AS interview_count
    FROM interviews i
    JOIN applications a ON i.application_id = a.application_id
    WHERE a.student_id = ?
";
$stmtInterview = $conn->prepare($sqlInterview);
$stmtInterview->bind_param("i", $student_id);
$stmtInterview->execute();
$resultInterview = $stmtInterview->get_result();
$rowInterview = $resultInterview->fetch_assoc();
$stats['interview'] = $rowInterview['interview_count'];

// ======================
// Latest 3 internships
// ======================
$sql = "
    SELECT 
        id,
        title,
        company_name,
        location,
        stipend,
        created_at
    FROM postposition
    ORDER BY created_at DESC
    LIMIT 3
";
$recommended = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Portal - Overview</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="/project/css/Student/overview.css">
  <style>
    .internship-card {
      display: block;
      border: 1px solid #ddd;
      padding: 15px;
      margin-bottom: 10px;
      text-decoration: none;
      color: inherit;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    .internship-card:hover {
      background-color: #f9f9f9;
    }
    .label-new {
      background: #ff4757;
      color: #fff;
      padding: 3px 6px;
      font-size: 12px;
      border-radius: 4px;
      margin-bottom: 5px;
      display: inline-block;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <main class="main">

      <!-- Welcome -->
      <section class="welcome">
       <h1>Welcome, <?= htmlspecialchars($student_name) ?>!</h1>
      </section>

      <!-- Summary Cards -->
      <section class="summary">
        <div class="card">
          <i class="fas fa-clipboard-list card-icon"></i>
          <h3>Applications</h3>
          <p><?= $stats['applications'] ?></p>
        </div>

        <div class="card">
          <i class="fas fa-hourglass-half card-icon"></i>
          <h3>In Review</h3>
          <p><?= $stats['review'] ?></p>
        </div>

        <div class="card">
          <i class="fas fa-calendar-check card-icon"></i>
          <h3>Interviews</h3>
          <p><?= $stats['interview'] ?></p>
        </div>

        <div class="card">
          <i class="fas fa-check-circle card-icon"></i>
          <h3>Accepted</h3>
          <p><?= $stats['accepted'] ?></p>
        </div>
      </section>

      <!-- Recommended Internships -->
      <div class="dashboard-row">
        <div class="outerlayer">
          <section>
            <h2>Recommended Internships</h2>

            <?php if ($recommended->num_rows == 0): ?>
                <p>No recommended internships available right now.</p>
            <?php else: ?>
                <?php while ($row = $recommended->fetch_assoc()): ?>
                    <div class="internship-card" onclick="window.parent.loadBrowseInternship(<?= $row['id'] ?>)">
                      <?php
                        $createdAt = new DateTime($row['created_at']);
                        $now = new DateTime();
                        $interval = $createdAt->diff($now)->days;
                        if ($interval <= 2): ?>
                          <span class="label-new">New</span>
                      <?php endif; ?>

                      <h3><?= htmlspecialchars($row['title']); ?></h3>
                      <p>
                        <?= htmlspecialchars($row['company_name']); ?> • 
                        <?= htmlspecialchars($row['location']); ?>
                      </p>
                      <p>Rs.<?= number_format($row['stipend']); ?>/month</p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

          </section>
        </div>
      </div>

    </main>
  </div>
</body>
</html>