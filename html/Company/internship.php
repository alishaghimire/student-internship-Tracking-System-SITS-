<?php
if (!isset($_SESSION['company_id'])) {
    echo "<p style='color:red; padding:10px;'>Unauthorized access.</p>";
    return;
}

$company_id = (int) $_SESSION['company_id'];

$sql = "
    SELECT
        p.id,
        p.title,
        p.department,
        p.location,
        p.type,
        p.stipend,
        p.duration,
        p.deadline,
        p.description,
        p.created_at,
        GROUP_CONCAT(DISTINCT s.skill ORDER BY s.skill SEPARATOR ', ') AS skills,
        GROUP_CONCAT(DISTINCT r.responsibility ORDER BY r.responsibility SEPARATOR ', ') AS responsibilities
    FROM postposition AS p
    LEFT JOIN position_skills AS s ON p.id = s.position_id
    LEFT JOIN position_responsibilities AS r ON p.id = r.position_id
    WHERE p.company_id = ?
    GROUP BY p.id
    ORDER BY p.id DESC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

function computeStatus($deadline) {
    if (!$deadline) return 'active';
    $today = new DateTime('today');
    $due = DateTime::createFromFormat('Y-m-d', $deadline);
    return ($due && $due >= $today) ? 'active' : 'closed';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Internship Positions</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
  font-family: 'Inter', sans-serif;
  background:#f4f6fb;
  margin:0;
}

/* Header */
.header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:25px;
}
.header h1{
  font-size:1.8rem;
}
.count{
  color:#6b7280;
  font-size:.9rem;
}
.post-btn{
  background:#4361ee;
  color:#fff;
  border:none;
  padding:12px 18px;
  border-radius:10px;
  font-weight:600;
  cursor:pointer;
}
.post-btn i{margin-right:6px}

/* Cards */
.internship-list{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
  gap:20px;
}
.internship-card{
  background:#fff;
  border-radius:16px;
  padding:22px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  transition:.3s;
}
.internship-card:hover{
  transform:translateY(-6px);
}

/* Title & Status */
.card-title{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:10px;
}
.card-title h3{
  font-size:1.2rem;
}
.status{
  padding:5px 12px;
  border-radius:20px;
  font-size:.75rem;
  font-weight:700;
  text-transform:uppercase;
}
.status.active{background:#e0f7ec;color:#059669}
.status.closed{background:#fee2e2;color:#dc2626}

/* Info */
.info{
  font-size:.9rem;
  color:#4b5563;
  margin-bottom:10px;
}
.info span{
  display:block;
  margin-bottom:4px;
}

/* Skills Chips */
.skill-chips{
  display:flex;
  flex-wrap:wrap;
  gap:6px;
  margin-top:6px;
}
.skill-chips span{
  background:#eef2ff;
  color:#4338ca;
  padding:4px 10px;
  border-radius:999px;
  font-size:.75rem;
  font-weight:600;
}

/* Responsibilities */
.responsibilities{
  margin-top:12px;
}
.responsibilities ul{
  padding-left:18px;
  margin-top:6px;
}
.responsibilities li{
  font-size:.85rem;
  color:#374151;
  margin-bottom:4px;
}

/* CTA */
.internship-btn{
  width:100%;
  margin-top:16px;
  padding:12px;
  border:none;
  border-radius:12px;
  background:linear-gradient(135deg,#4361ee,#3a0ca3);
  color:#fff;
  font-weight:600;
  cursor:pointer;
}
.internship-btn:hover{opacity:.9}
</style>
</head>

<body>

<div class="header">
  <div>
    <h1>Internship Positions</h1>
    <span class="count"><?= $result->num_rows ?> positions</span>
  </div>
  <button class="post-btn" onclick="location.href='postposition.php'">
    <i class="fas fa-plus"></i> Post Internship
  </button>
</div>

<div class="internship-list">

<?php while($row=$result->fetch_assoc()): 
  $status = computeStatus($row['deadline']);
?>

<div class="internship-card">

  <div class="card-title">
    <h3><?= htmlspecialchars($row['title']) ?></h3>
    <span class="status <?= $status ?>"><?= ucfirst($status) ?></span>
  </div>

  <div class="info">
    <span><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></span>
    <span><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></span>
    <span><strong>Type:</strong> <?= htmlspecialchars($row['type']) ?></span>
    <span><strong>Stipend:</strong> Rs <?= number_format($row['stipend'],2) ?>/month</span>
    <span><strong>Duration:</strong> <?= htmlspecialchars($row['duration']) ?></span>
    <span><strong>Deadline:</strong> <?= htmlspecialchars($row['deadline']) ?></span>
  </div>

  <?php if($row['skills']): ?>
    <strong>Skills</strong>
    <div class="skill-chips">
      <?php foreach(explode(',',$row['skills']) as $skill): ?>
        <span><?= htmlspecialchars(trim($skill)) ?></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if($row['responsibilities']): ?>
  <div class="responsibilities">
    <strong>Responsibilities</strong>
    <ul>
      <?php foreach(explode(',',$row['responsibilities']) as $r): ?>
        <li><?= htmlspecialchars(trim($r)) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <button class="internship-btn"
    onclick="location.href='applications.php?internship_id=<?= (int)$row['id'] ?>'">
    View Applications
  </button>

</div>

<?php endwhile; ?>

</div>

</body>
</html>
