<?php
require_once "collegedb_connection.php";

if (!isset($_SESSION['college_id'])) {
    die("Error: Unauthorized access.");
}
$college_id = $_SESSION['college_id'];

// Approve action
if (isset($_POST['approve_id'])) {
    $approve_id = intval($_POST['approve_id']);
    $update = $conn->prepare("UPDATE logbook_entries SET approval_status = 'approved' WHERE entry_id = ?");
    $update->bind_param("i", $approve_id);
    $update->execute();
    $update->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch entries for this college
$query = "
    SELECT * FROM logbook_entries 
    WHERE student_id IN (
        SELECT student_id FROM students_registrtaion WHERE college_id = ?
    )
    ORDER BY entry_date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $college_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logbook Entries</title>
  <style>
    body { font-family: "Poppins", sans-serif; background: #f5f7fa; margin: 0; padding: 20px; color: #333; }
    .no-data { text-align: center; font-size: 1rem; color: #666; margin-top: 40px; }
    .log-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: transform 0.3s ease; }
    .log-card:hover { transform: translateY(-4px); }
    .log-header { display: flex; justify-content: flex-end; margin-bottom: 10px; }
    .status.completed { background: green; color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; }
    .btn { border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; border-radius: 20px; padding: 8px 16px; transition: all 0.3s ease; }
    .approve-btn { background: linear-gradient(135deg, #28a745, #218838); color: #fff; }
    .approve-btn:hover { background: linear-gradient(135deg, #218838, #28a745); }
    .view-btn { background: linear-gradient(135deg, #2575fc, #6a11cb); color: #fff; margin-top: 10px; }
    .view-btn:hover { background: linear-gradient(135deg, #6a11cb, #2575fc); }
    .details { display: flex; justify-content: space-between; margin-top: 10px; font-size: 0.9rem; color: #555; }

    #overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.35); opacity: 0; pointer-events: none; transition: opacity 0.3s ease; z-index: 998; }
    #overlay.active { opacity: 1; pointer-events: auto; }
    #slidePanel { position: fixed; top: 0; right: -100%; width: 70%; max-width: 900px; height: 100%; background: #fff; box-shadow: -4px 0 12px rgba(0,0,0,0.2); transition: right 0.3s ease; z-index: 999; overflow-y: auto; padding: 20px 24px 40px; }
    #slidePanel.active { right: 0; }
    #closePanel { position: absolute; top: 15px; right: 20px; background: #ff512f; color: #fff; border: none; font-size: 1.2rem; padding: 6px 12px; border-radius: 50%; cursor: pointer; transition: background 0.3s ease; }
    #closePanel:hover { background: #dd2476; }
  </style>
</head>
<body>

<?php
if ($result->num_rows === 0) {
    echo "<p class='no-data'>No logbook entries found.</p>";
}

while ($row = $result->fetch_assoc()):
    $id = $row['entry_id'];
?>
<div class="log-card">
  <div class="log-header">
    <?php if ($row['approval_status'] === 'approved'): ?>
      <span class="status completed">Approved</span>
    <?php else: ?>
      <form method="POST" style="display:inline;">
        <input type="hidden" name="approve_id" value="<?= $id ?>">
        <button class="btn approve-btn" type="submit">Approve</button>
      </form>
    <?php endif; ?>
  </div>

  <p><strong>Student:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
  <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
  <p><strong>Supervisor:</strong> <?= htmlspecialchars($row['supervisor']) ?></p>

  <div class="details">
    <span><strong>Date:</strong> <?= $row['entry_date'] ?></span>
    <span><strong>Hours:</strong> <?= $row['hours'] ?></span>
  </div>

  <div class="actions">
    <button class="btn view-btn" data-id="<?= $id ?>">View</button>
  </div>
</div>
<?php endwhile; ?>

<!-- Overlay + Slide Panel -->
<div id="overlay"></div>
<div id="slidePanel">
  <button id="closePanel">&times;</button>
  <div id="slideContent"></div>
</div>

<script>
const overlay = document.getElementById('overlay');
const slidePanel = document.getElementById('slidePanel');
const closePanelBtn = document.getElementById('closePanel');
const slideContent = document.getElementById('slideContent');

// Open panel with details
document.querySelectorAll(".view-btn").forEach(btn => {
  btn.addEventListener("click", function () {
    const id = this.getAttribute("data-id");
    fetch("viewdetails.php?id=" + id)
      .then(res => res.text())
      .then(html => {
        slideContent.innerHTML = html;
        slidePanel.classList.add('active');
        overlay.classList.add('active');
      });
  });
});

// Close panel via cross
closePanelBtn.addEventListener("click", () => {
  slidePanel.classList.remove('active');
  overlay.classList.remove('active');
  slideContent.innerHTML = "";
});

// Close panel by clicking overlay
overlay.addEventListener("click", () => {
  slidePanel.classList.remove('active');
  overlay.classList.remove('active');
  slideContent.innerHTML = "";
});
</script>

</body>
</html>