<?php
session_start();
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Get student_id from query string
$student_id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM students_registrtaion WHERE student_id=? LIMIT 1");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    die("Student not found.");
}

// Avatar initials
$parts = explode(" ", $student['full_name']);
$initials = "";
foreach ($parts as $p) { $initials .= strtoupper($p[0]); }

// Status class
$statusClass = strtolower($student['status']); // pending, approved, rejected
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Profile</title>
  <link rel="stylesheet" href="studentProfile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

  <!-- Back Button OUTSIDE the profile-container -->
  <a href="AdminDashboard.php" class="btn-back">
    <i class="fa-solid fa-arrow-left"></i> Back
  </a>

  <div class="profile-container">

    <!-- Header -->
    <div class="profile-header">
      <div class="avatar"><?php echo $initials; ?></div>
      <div class="info">
        <h2><?php echo htmlspecialchars($student['full_name']); ?></h2>
        <p>Student ID: <?php echo htmlspecialchars($student['student_code']); ?> •  
           <span class="status <?php echo $statusClass; ?>">
             <?php echo ucfirst($student['status']); ?>
           </span>
        </p>
      </div>
    </div>

    <!-- Basic Information -->
    <section class="profile-section">
      <h3><i class="fa-solid fa-id-card"></i> Basic Information</h3>
      <ul>
        <li><strong>Email Address:</strong> <?php echo htmlspecialchars($student['email']); ?></li>
        <li><strong>Phone Number:</strong> <?php echo htmlspecialchars($student['phone']); ?></li>
        <li><strong>Department:</strong> <?php echo htmlspecialchars($student['major']); ?></li>
        <li><strong>College:</strong> <?php echo htmlspecialchars($student['college_name']); ?></li>
        <li><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_code']); ?></li>
      </ul>
    </section>

    <!-- Skills -->
    <section class="profile-section">
      <h3><i class="fa-solid fa-lightbulb"></i> Skills & Expertise</h3>
      <div class="skills">
        <span class="skill">React</span>
        <span class="skill">Python</span>
        <span class="skill">Java</span>
        <span class="skill">SQL</span>
        <span class="skill">Git</span>
      </div>
    </section>

    <!-- Internship Statistics -->
    <section class="profile-section">
      <h3><i class="fa-solid fa-chart-line"></i> Internship Statistics</h3>
      <ul>
        <li><strong>Applications:</strong> 12</li>
        <li><strong>Accepted:</strong> 2</li>
        <li><strong>Completed:</strong> 1</li>
      </ul>
    </section>

    <!-- Activity Information -->
    <section class="profile-section">
      <h3><i class="fa-solid fa-clock"></i> Activity Information</h3>
      <ul>
        <li><strong>Joined Date:</strong> <?php echo date("M Y", strtotime($student['created_at'])); ?></li>
        <li><strong>Last Updated:</strong> <?php echo date("M d, Y H:i", strtotime($student['updated_at'])); ?></li>
      </ul>
    </section>

    <!-- Actions -->
    <div class="profile-actions">
      <a href="viewstudentapplication.php?student=<?php echo $student['student_id']; ?>" class="btn view">
        <i class="fa-solid fa-folder-open"></i> View Applications
      </a>
      <a href="contactStudent.php?id=<?php echo $student['student_id']; ?>" class="btn contact">
        <i class="fa-solid fa-envelope"></i> Contact Student
      </a>
    </div>
  </div>
</body>
</html>