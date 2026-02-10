<?php
session_start();
require_once "collegedb_connection.php";

// Ensure college is logged in
if (!isset($_SESSION['college_id'])) {
    die("Unauthorized access.");
}
$college_id = $_SESSION['college_id'];

// Ensure log entry ID is provided
if (!isset($_GET['id'])) {
    die("Missing log entry ID.");
}
$entry_id = intval($_GET['id']);

// Fetch logbook entry + verify college owns the student
$query = "
    SELECT le.entry_id, le.student_id, le.full_name, le.title, le.entry_date,
           le.hours, le.location, le.supervisor, le.approval_status,
           sr.college_id
    FROM logbook_entries le
    JOIN students_registrtaion sr ON le.student_id = sr.student_id
    WHERE le.entry_id = ? AND sr.college_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $entry_id, $college_id);
$stmt->execute();
$entry = $stmt->get_result()->fetch_assoc();

if (!$entry) {
    die("No permission or entry not found.");
}

// Fetch tasks for this entry
$taskQuery = $conn->prepare("SELECT task_text FROM logbook_tasks WHERE entry_id = ?");
$taskQuery->bind_param("i", $entry_id);
$taskQuery->execute();
$tasks = $taskQuery->get_result();

// Fetch learnings for this entry
$learnQuery = $conn->prepare("SELECT learning_text FROM logbook_learnings WHERE entry_id = ?");
$learnQuery->bind_param("i", $entry_id);
$learnQuery->execute();
$learnings = $learnQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Work Report</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #f5f7fa;
      font-family: "Poppins", sans-serif;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .report-container {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
      transition: all 0.3s ease;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 0;
      right: 0;
      background: transparent;
      border: none;
      font-size: 1.2rem;
      cursor: pointer;
      color: #666;
      transition: color 0.3s ease;
    }
    .close-btn:hover { color: #ff512f; }

    .title {
      font-size: 1.8rem;
      font-weight: 600;
      color: #2575fc;
      margin-bottom: 10px;
    }

    .subtitle {
      font-size: 1rem;
      color: #666;
      margin-bottom: 15px;
    }

    .report-date {
      font-size: 0.9rem;
      color: #999;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .info-card {
      background: #f9fafc;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .info-card i {
      font-size: 1.5rem;
      color: #2575fc;
      margin-bottom: 8px;
    }

    .info-card strong {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #444;
    }

    .section {
      margin-bottom: 30px;
    }

    .section h2 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #2575fc;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .task-list, .learning-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .task-list li, .learning-list li {
      background: #f9fafc;
      margin-bottom: 10px;
      padding: 12px 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      font-size: 0.95rem;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
    }

    .footer-close {
      background: linear-gradient(135deg, #fcfbfdff, #ffffffff);
      border: none;
      padding: 12px 25px;
      border-radius: 30px;
      color: #030303ff;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .footer-close:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

<div class="report-container">

  <div class="header">
    <button class="close-btn" id="closeBtn"><i class="fas fa-times"></i></button>
    <h1 class="title"><?= htmlspecialchars($entry['title']) ?></h1>
    <p class="subtitle">Summary of daily activities and accomplishments</p>
    <div class="report-date">
      <i class="far fa-calendar-alt"></i>
      Generated on: <?= htmlspecialchars($entry['entry_date']) ?>
    </div>
  </div>

  <div class="info-grid">
    <div class="info-card">
      <i class="far fa-calendar"></i>
      <strong>Date</strong>
      <div><?= htmlspecialchars($entry['entry_date']) ?></div>
    </div>
    <div class="info-card">
      <i class="far fa-clock"></i>
      <strong>Hours Worked</strong>
      <div><?= htmlspecialchars($entry['hours']) ?> hours</div>
    </div>
    <div class="info-card">
      <i class="fas fa-map-marker-alt"></i>
      <strong>Location</strong>
      <div><?= htmlspecialchars($entry['location']) ?></div>
    </div>
    <div class="info-card">
      <i class="fas fa-user-tie"></i>
      <strong>Supervisor</strong>
      <div><?= htmlspecialchars($entry['supervisor']) ?></div>
    </div>
  </div>

  <section class="section">
    <h2><i class="fas fa-tasks"></i> Tasks Completed</h2>
    <ul class="task-list">
      <?php while ($t = $tasks->fetch_assoc()): ?>
        <li><?= htmlspecialchars($t['task_text']) ?></li>
      <?php endwhile; ?>
    </ul>
  </section>

  <section class="section">
    <h2><i class="fas fa-graduation-cap"></i> Key Learnings</h2>
    <ul class="learning-list">
      <?php while ($l = $learnings->fetch_assoc()): ?>
        <li><?= htmlspecialchars($l['learning_text']) ?></li>
      <?php endwhile; ?>
    </ul>
  </section>

  <div class="footer">
  <a class="footer-close" 
     href="feedback.php?entry_id=<?= $entry['entry_id'] ?>">
     <i class="fas fa-comment-dots"></i> Give Feedback
  </a>
</div>


</div>

<script>
document.getElementById('closeBtn').addEventListener('click', function() {
  document.querySelector('.report-container').style.opacity = '0';
  document.querySelector('.report-container').style.transform = 'scale(0.9)';
  setTimeout(() => {
    window.location.href = "fetch_logbook_entries.php";
  }, 300);
});

document.getElementById('footerCloseBtn').addEventListener('click', function() {
  this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
  setTimeout(() => {
    this.innerHTML = '<i class="fas fa-check-circle"></i> Submitted!';
    window.location.href = "fetch_logbook_entries.php";
  }, 1500);
});
</script>

</body>
</html>