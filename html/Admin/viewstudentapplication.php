<?php
session_start();
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Get student_id from query string
$student_id = intval($_GET['student'] ?? 0);

// Fetch student info
$stmtStudent = $conn->prepare("SELECT full_name, email FROM students_registrtaion WHERE student_id=? LIMIT 1");
$stmtStudent->bind_param("i", $student_id);
$stmtStudent->execute();
$resStudent = $stmtStudent->get_result();
$student = $resStudent->fetch_assoc();
$stmtStudent->close();

if (!$student) {
    die("Student not found.");
}

// Count applications
$stmtCount = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE student_id=?");
$stmtCount->bind_param("i", $student_id);
$stmtCount->execute();
$resCount = $stmtCount->get_result();
$totalApps = $resCount->fetch_assoc()['total'];
$stmtCount->close();

// Fetch application details
$sql = "SELECT application_id, post_id, company_id, status, created_at 
        FROM applications WHERE student_id=? ORDER BY created_at DESC";
$stmtApps = $conn->prepare($sql);
$stmtApps->bind_param("i", $student_id);
$stmtApps->execute();
$resApps = $stmtApps->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Applications - <?php echo htmlspecialchars($student['full_name']); ?></title>
  <link rel="stylesheet" href="viewstudentapplication.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <div class="applications-container">

    <!-- Back button -->
<a href="studentProfile.php?id=<?php echo $student_id; ?>" class="btn-back">
   <i class="fa-solid fa-arrow-left"></i> Back
</a>
    <header class="header">
      <h1><i class="fa-solid fa-folder-open"></i> Applications for <?php echo htmlspecialchars($student['full_name']); ?></h1>
      <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
      <p>Total Applications: <strong><?php echo $totalApps; ?></strong></p>
    </header>

    <table class="applications-table">
      <thead>
        <tr>
          <th>Application ID</th>
          <th>Post ID</th>
          <th>Company ID</th>
          <th>Status</th>
          <th>Submitted On</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($resApps && $resApps->num_rows > 0): ?>
          <?php while($row = $resApps->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['application_id']; ?></td>
              <td><?php echo $row['post_id']; ?></td>
              <td><?php echo $row['company_id']; ?></td>
              <td><span class="status <?php echo strtolower($row['status']); ?>">
                <?php echo ucfirst($row['status']); ?>
              </span></td>
              <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>
              <td>
                <a href="applicationDetailsForstudent.php?id=<?php echo $row['application_id']; ?>" class="btn-view">
                  <i class="fa-solid fa-eye"></i> View
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">No applications found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>