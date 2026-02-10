<?php
session_start();
require_once "studentdbconnection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: studentLogin.php");
    exit;
}

$student_id = (int) $_SESSION['student_id'];

/* ======================
   FETCH APPLICATIONS + INTERVIEW INFO
====================== */
$sql = "
SELECT 
    a.application_id,
    a.status,
    a.created_at,
    p.title,
    p.type,
    p.location,
    c.company_name,
    c.city_state,
    s3.resume_path,
    i.interview_date,
    i.interview_time,
    i.duration,
    i.interview_type,
    i.interviewer,
    i.meeting_link,
    i.location AS interview_location
FROM applications a
JOIN postposition p ON a.post_id = p.id
JOIN company_registration c ON p.company_id = c.company_id
LEFT JOIN application_step3 s3 ON a.application_id = s3.application_id
LEFT JOIN interviews i ON a.application_id = i.application_id
WHERE a.student_id = ?
ORDER BY a.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

/* ======================
   CALCULATE SUMMARY STATS
====================== */
$stats = [
    'total' => 0,
    'pending' => 0,
    'review' => 0,
    'interview' => 0,
    'accepted' => 0,
    'rejected' => 0
];

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
    $stats['total']++;

    $status = strtolower($row['status']);
    switch ($status) {
        case 'shortlisted':
        case 'accepted':
        case 'offer_accepted':
            $stats['accepted']++;
            break;
        case 'pending':
            $stats['pending']++;
            break;
        case 'reviewed':
            $stats['review']++;
            break;
        case 'interview':
            $stats['interview']++;
            break;
        case 'reject':
        case 'rejected':
            $stats['rejected']++;
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Applications</title>
  <link rel="stylesheet" href="/project/css/Student/myapplicationOffer.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .tabs button { cursor: pointer; }
    .app-card { display: block; margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
    .hidden { display: none; }
    .interview-card {
      background: #f9f9f9;
      border: 2px solid #007bff;
      padding: 15px;
      margin-top: 15px;
      border-radius: 8px;
    }
    .interview-card h3 { margin: 0 0 10px; color:#007bff; }
    .interview-card p { margin: 5px 0; }
    .interview-card .btn {
      display:inline-block; padding:6px 12px; background:#28a745; color:#fff;
      text-decoration:none; border-radius:4px; margin-top:10px;
    }
    .interview-card .btn:hover { background:#218838; }
  </style>
</head>
<body>
  <header>
    <h1>My Applications</h1>
    <div class="summary-boxes">
      <div class="box total"><i class="fas fa-clipboard-list"></i> Total: <?= $stats['total'] ?></div>
      <div class="box pending"><i class="fas fa-hourglass-half"></i> Pending: <?= $stats['pending'] ?></div>
      <div class="box review"><i class="fas fa-search"></i> Under Review: <?= $stats['review'] ?></div>
      <div class="box interview"><i class="fas fa-comments"></i> Interviews: <?= $stats['interview'] ?></div>
      <div class="box accepted"><i class="fas fa-check-circle"></i> Accepted: <?= $stats['accepted'] ?></div>
      <div class="box rejected"><i class="fas fa-times-circle"></i> Not Selected: <?= $stats['rejected'] ?></div>
    </div>
  </header>

  <nav class="tabs">
    <button class="active" data-status="all">All</button>
    <button data-status="pending">Pending</button>
    <button data-status="reviewed">Under Review</button>
    <button data-status="accepted">Accepted</button>
    <button data-status="rejected">Not Selected</button>
  </nav>

  <section class="applications">
    <?php if (!empty($applications)): ?>
      <?php foreach ($applications as $app): ?>
        <?php
          $status = strtolower($app['status']);
          $displayStatus = ucfirst($status);

          switch ($status) {
            case 'shortlisted':
              $status = 'accepted';
              $displayStatus = 'Accepted';
              break;
            case 'offer_accepted':
              $status = 'accepted';
              $displayStatus = 'Offer Accepted';
              break;
            case 'reviewed':
              $displayStatus = 'Under Review';
              break;
            case 'pending':
              $displayStatus = 'Pending';
              break;
            case 'reject':
            case 'rejected':
              $status = 'rejected';
              $displayStatus = 'Not Selected';
              break;
            default:
              $status = 'unknown';
              $displayStatus = '—';
              break;
          }
        ?>
        <div class="app-card" data-status="<?= $status ?>">
          <h2><?= htmlspecialchars($app['title']) ?></h2>
          <p class="company"><?= htmlspecialchars($app['company_name']) ?> — <?= htmlspecialchars($app['city_state'] . ' | ' . $app['location']) ?></p>
          <p class="type"><?= htmlspecialchars($app['type']) ?></p>
          <p class="applied">Applied: <?= date("d/m/Y", strtotime($app['created_at'])) ?></p>
          <p class="status">Status: <strong><?= $displayStatus ?></strong></p>

          <?php if (!empty($app['interview_date'])): ?>
            <div class="interview-card">
              <h3>Interview Scheduled</h3>
              <p><strong>Date:</strong> <?= date("M d, Y", strtotime($app['interview_date'])) ?></p>
              <p><strong>Time:</strong> <?= date("h:i A", strtotime($app['interview_time'])) ?></p>
              <p><strong>Duration:</strong> <?= (int)$app['duration'] ?> mins</p>
              <p><strong>Type:</strong> <?= htmlspecialchars($app['interview_type']) ?></p>
              <p><strong>Interviewer:</strong> <?= htmlspecialchars($app['interviewer']) ?></p>
              <p><strong>Location:</strong> <?= htmlspecialchars($app['interview_location']) ?></p>
              <?php if ($app['interview_type'] === 'Video' && !empty($app['meeting_link'])): ?>
                <a class="btn" href="<?= htmlspecialchars($app['meeting_link']) ?>" target="_blank">Join Meeting</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <div class="actions">
            <a href="viewApplication.php?app_id=<?= $app['application_id'] ?>"><button>View Application</button></a>
            <?php if (!empty($app['resume_path'])): ?>
              <a href="<?= $app['resume_path'] ?>" download><button>Download Resume</button></a>
            <?php endif; ?>
            <?php if ($status === 'accepted' && $displayStatus !== 'Offer Accepted'): ?>
              <form method="post" action="acceptOffer.php" style="display:inline;">
                <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                
              </form>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No applications found.</p>
    <?php endif; ?>
  </section>

<script>
const tabs = document.querySelectorAll('.tabs button');
const cards = document.querySelectorAll('.app-card');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    const status = tab.getAttribute('data-status');
    cards.forEach(card => {
      if (status === 'all' || card.dataset.status === status) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  });
});
</script>

</body>
</html>