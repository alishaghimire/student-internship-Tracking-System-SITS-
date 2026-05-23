<?php
require_once "dbconnectcompany.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}

$company_id = (int) $_SESSION['company_id'];

/* ======================
   FETCH SHORTLISTED STUDENTS
====================== */
$sql = "
SELECT 
    s1.full_name,
    p.title AS position,
    a.application_id,
    a.status,
    i.interview_date,
    i.interview_time,
    i.interviewer
FROM applications a
JOIN application_step1 s1 ON a.application_id = s1.application_id
JOIN postposition p ON a.post_id = p.id
LEFT JOIN interviews i ON a.application_id = i.application_id
WHERE a.company_id = ? AND a.status='shortlisted'
ORDER BY s1.full_name ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="table-wrapper">
<h2>Shortlisted Students</h2>
<table class="internship-table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Position</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['position']) ?></td>
          <td><span class="status active"><?= ucfirst($row['status']) ?></span></td>
          <td><a href="studentProfile.php?student_id=<?= $row['application_id'] ?>" class="action-link">View Profile</a></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="4" style="text-align:center;">No shortlisted students found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
</div>
