<?php
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Fetch students from DB
$sql = "SELECT student_id, full_name, student_code, email, major, college_name, status, created_at 
        FROM students_registrtaion ORDER BY full_name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Management</title>
  <link rel="stylesheet" href="student.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <div class="student-container">

    <table class="student-table">
      <thead>
        <tr>
          <th>Student</th>
          <th>Email</th>
          <th>Student ID</th>
          <th>Major</th>
          <th>College</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <?php
              // Avatar initials
              $parts = explode(" ", $row['full_name']);
              $initials = "";
              foreach ($parts as $p) { $initials .= strtoupper($p[0]); }

              // Status class
              $statusClass = strtolower($row['status']); // pending, approved, rejected
            ?>
            <tr>
              <td><div class="avatar"><?php echo $initials; ?></div> <?php echo htmlspecialchars($row['full_name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['student_code']); ?></td>
              <td><?php echo htmlspecialchars($row['major']); ?></td>
              <td><?php echo htmlspecialchars($row['college_name']); ?></td>
              <td><span class="status <?php echo $statusClass; ?>">
                <?php echo ucfirst($row['status']); ?>
              </span></td>
              <td>
                <a href="studentProfile.php?id=<?php echo $row['student_id']; ?>" class="btn-view">View All</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7">No students found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>