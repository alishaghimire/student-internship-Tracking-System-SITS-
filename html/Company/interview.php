<?php
// Start buffering and session at the very top
ob_start();


require_once "dbconnectcompany.php";

/* ======================
   AUTH CHECK
====================== */
if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}
$company_id = (int)$_SESSION['company_id'];

$flash = []; // collect success/error messages

/* ======================
   HANDLE INTERVIEW SCHEDULING (SAFE + PRG)
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule'])) {
    $application_id = isset($_POST['application_id']) ? (int)$_POST['application_id'] : 0;
    $date           = $_POST['date']    ?? '';
    $hour           = isset($_POST['hour'])   ? (int)$_POST['hour']   : null;
    $minute         = isset($_POST['minute']) ? (int)$_POST['minute'] : null;
    $ampm           = $_POST['ampm']    ?? '';
    $duration       = isset($_POST['duration']) ? (int)$_POST['duration'] : null;
    $type           = $_POST['type']    ?? '';
    $interviewer    = $_POST['interviewer'] ?? '';
    $link           = $_POST['link']    ?? '';
    $location       = $_POST['location'] ?? '';

    // Validation
    if ($application_id <= 0) $flash[] = ['type'=>'error','text'=>'Please select a shortlisted candidate.'];
    if ($date === '') $flash[] = ['type'=>'error','text'=>'Please choose an interview date.'];
    if ($hour === null || $minute === null || ($ampm !== 'AM' && $ampm !== 'PM')) $flash[] = ['type'=>'error','text'=>'Please provide a valid time with AM/PM.'];
    if ($duration === null || $duration <= 0) $flash[] = ['type'=>'error','text'=>'Please provide a valid duration.'];
    if ($type === '') $flash[] = ['type'=>'error','text'=>'Please select an interview type.'];
    if ($location === '') $flash[] = ['type'=>'error','text'=>'Please provide a location.'];

    if (empty($flash)) {
        // Convert to 24h time
        if ($ampm === "PM" && $hour < 12) $hour += 12;
        if ($ampm === "AM" && $hour == 12) $hour = 0;
        if ($minute < 0) $minute = 0;
        if ($minute > 59) $minute = 59;
        $time = sprintf("%02d:%02d:00", $hour, $minute);

        // Fetch candidate name and role
        $stmt = $conn->prepare("
            SELECT s1.full_name, p.title 
            FROM applications a
            JOIN application_step1 s1 ON a.application_id = s1.application_id
            JOIN postposition p ON a.post_id = p.id
            WHERE a.application_id = ?
        ");
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $stmt->bind_result($candidate_name, $role);
        $stmt->fetch();
        $stmt->close();

        // Duplicate check
        $check = $conn->prepare("SELECT id FROM interviews WHERE application_id=? AND interview_date=? AND interview_time=?");
        $check->bind_param("iss", $application_id, $date, $time);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $flash[] = ['type'=>'error','text'=>'Interview already scheduled for this candidate at that time.'];
        } else {
            // Insert interview
            $stmt = $conn->prepare("
                INSERT INTO interviews 
                (application_id, candidate_name, role, interview_date, interview_time, duration, interview_type, interviewer, meeting_link, location)
                VALUES (?,?,?,?,?,?,?,?,?,?)
            ");
            $stmt->bind_param(
                "issssissss",
                $application_id,
                $candidate_name,
                $role,
                $date,
                $time,
                $duration,
                $type,
                $interviewer,
                $link,
                $location
            );
            if ($stmt->execute()) {
                // Update application status
                $update = $conn->prepare("UPDATE applications SET status='interview' WHERE application_id=?");
                $update->bind_param("i", $application_id);
                $update->execute();
                $update->close();

                // Redirect to avoid resubmission
                header("Location: interview.php?success=1");
                exit;
            } else {
                $flash[] = ['type'=>'error','text'=>'Database error: could not save interview.'];
            }
            $stmt->close();
        }
        $check->close();
    }
}

/* ======================
   FLASH MESSAGE FROM REDIRECT
====================== */
if (isset($_GET['success'])) {
    $flash[] = ['type'=>'success','text'=>'Interview scheduled successfully.'];
}

/* ======================
   FETCH SHORTLISTED APPLICANTS
====================== */
$shortlisted = $conn->prepare("
    SELECT a.application_id, s1.full_name, p.title AS role
    FROM applications a
    JOIN application_step1 s1 ON a.application_id = s1.application_id
    JOIN postposition p ON a.post_id = p.id
    WHERE a.status = 'shortlisted' AND a.company_id = ?
    ORDER BY s1.full_name
");
$shortlisted->bind_param("i", $company_id);
$shortlisted->execute();
$shortlisted_result = $shortlisted->get_result();

/* ======================
   FETCH INTERVIEWS
====================== */
$today = date("Y-m-d");

$today_interviews = $conn->prepare("
    SELECT i.*, s1.full_name, p.title AS role
    FROM interviews i
    JOIN applications a ON i.application_id = a.application_id
    JOIN application_step1 s1 ON a.application_id = s1.application_id
    JOIN postposition p ON a.post_id = p.id
    WHERE a.company_id = ? AND i.interview_date = ?
    ORDER BY i.interview_time
");
$today_interviews->bind_param("is", $company_id, $today);
$today_interviews->execute();
$today_result = $today_interviews->get_result();

$upcoming_interviews = $conn->prepare("
    SELECT i.*, s1.full_name, p.title AS role
    FROM interviews i
    JOIN applications a ON i.application_id = a.application_id
    JOIN application_step1 s1 ON a.application_id = s1.application_id
    JOIN postposition p ON a.post_id = p.id
    WHERE a.company_id = ? AND i.interview_date > CURDATE()
    ORDER BY i.interview_date, i.interview_time
");
$upcoming_interviews->bind_param("i", $company_id);
$upcoming_interviews->execute();
$upcoming_result = $upcoming_interviews->get_result();

// No closing PHP tag to avoid accidental whitespace
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Interview Schedule</title>
<style>
body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:0; }
.container { width:90%; max-width:1000px; margin:30px auto; background:#fff; padding:20px; border-radius:8px; }
h1 { text-align:center; margin-bottom:20px; }
.flash { padding:10px; border-radius:6px; margin-bottom:15px; }
.flash.success { background:#e6ffed; color:#1a7f37; border:1px solid #b7f5c2; }
.flash.error { background:#ffecec; color:#b00020; border:1px solid #ffb3b3; }
form { background:#eef; padding:15px; border-radius:6px; margin-bottom:30px; }
form label { font-weight:bold; margin-top:8px; display:block; }
form input, form select, form button { display:block; width:100%; margin:8px 0; padding:8px; }
form button { background:#007bff; color:#fff; border:none; cursor:pointer; }
form button:hover { background:#0056b3; }
.row { display:flex; gap:12px; }
.row > * { flex:1; }
.interview-card { background:#fafafa; border:1px solid #ddd; padding:15px; margin:10px 0; border-radius:6px; }
.interview-card h3 { margin:0 0 10px; }
.btn { display:inline-block; padding:6px 12px; background:#28a745; color:#fff; text-decoration:none; border-radius:4px; }
.btn:hover { background:#218838; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
table th, table td { border:1px solid #ccc; padding:8px; text-align:left; }
table th { background:#f0f0f0; }
.section-title { margin-top:24px; font-size:18px; font-weight:bold; }
</style>
</head>
<body>
<div class="container">

<h1>Interview Schedule</h1>

<!-- Flash messages -->
<?php foreach ($flash as $msg): ?>
  <div class="flash <?= htmlspecialchars($msg['type']) ?>"><?= htmlspecialchars($msg['text']) ?></div>
<?php endforeach; ?>

<!-- ================= SCHEDULE FORM ================= -->
<form method="POST">
  <h2>Schedule Interview</h2>

  <label>Candidate (shortlisted)</label>
  <select name="application_id" required>
    <?php if ($shortlisted_result->num_rows > 0): ?>
      <?php while($row = $shortlisted_result->fetch_assoc()): ?>
        <option value="<?= $row['application_id'] ?>" data-role="<?= htmlspecialchars($row['role']) ?>">
          <?= htmlspecialchars($row['full_name']) ?> (<?= htmlspecialchars($row['role']) ?>)
        </option>
      <?php endwhile; ?>
    <?php else: ?>
      <option value="">No shortlisted candidates</option>
    <?php endif; ?>
  </select>

  <label>Role</label>
  <input type="text" name="role" id="roleInput" placeholder="Role" required>

  <div class="row">
    <div>
      <label>Date</label>
      <input type="date" name="date" required>
    </div>
    <div>
      <label>Time</label>
      <div class="row">
        <input type="number" name="hour" min="1" max="12" placeholder="HH" required>
        <input type="number" name="minute" min="0" max="59" placeholder="MM" required>
        <select name="ampm" required>
          <option value="AM">AM</option>
          <option value="PM">PM</option>
        </select>
      </div>
    </div>
  </div>

  <div class="row">
    <div>
      <label>Duration (minutes)</label>
      <input type="number" name="duration" min="1" placeholder="e.g., 30" required>
    </div>
    <div>
      <label>Type</label>
      <select name="type" required>
          <option value="Video">Video</option>
          <option value="Phone">Phone</option>
          <option value="In-Person">In-Person</option>
      </select>
    </div>
  </div>

  <div class="row">
    <div>
      <label>Interviewer</label>
      <input type="text" name="interviewer" placeholder="Interviewer Name">
    </div>
    <div>
      <label>Meeting Link (only for video)</label>
      <input type="text" name="link" placeholder="Meeting Link">
    </div>
  </div>

  <label>Location</label>
  <input type="text" name="location" placeholder="Location (e.g., Zoom, Office Room 301)" required>

  <button type="submit" name="schedule" value="1">Schedule Interview</button>
</form>

<!-- ================= TODAY'S INTERVIEWS ================= -->
<section class="today">
  <div class="section-title">Today's Interviews</div>
  <?php if ($today_result->num_rows > 0): ?>
    <?php while($row = $today_result->fetch_assoc()): ?>
      <div class="interview-card">
        <h3><?= htmlspecialchars($row['candidate_name'] ?: $row['full_name']); ?> – <?= htmlspecialchars($row['role']); ?></h3>
        <p>
          <?= date("M d, Y", strtotime($row['interview_date'])); ?>,
          <?= date("h:i A", strtotime($row['interview_time'])); ?> ·
          <?= (int)$row['duration']; ?> mins ·
          <?= htmlspecialchars($row['interview_type']); ?> ·
          Location: <?= htmlspecialchars($row['location']); ?>
        </p>
        <?php if ($row['interview_type'] === "Video" && !empty($row['meeting_link'])): ?>
          <a class="btn" href="<?= htmlspecialchars($row['meeting_link']); ?>" target="_blank">Join Meeting</a>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No interviews scheduled for today.</p>
  <?php endif; ?>
</section>

<!-- ================= UPCOMING INTERVIEWS ================= -->
<section class="upcoming">
  <div class="section-title">Upcoming Interviews</div>
  <?php if ($upcoming_result->num_rows > 0): ?>
    <table>
      <tr>
          <th>Date</th>
          <th>Candidate</th>
          <th>Role</th>
          <th>Type</th>
          <th>Interviewer</th>
          <th>Location</th>
      </tr>
      <?php while($row = $upcoming_result->fetch_assoc()): ?>
      <tr>
          <td><?= date("M d, Y h:i A", strtotime($row['interview_date']." ".$row['interview_time'])); ?></td>
          <td><?= htmlspecialchars($row['candidate_name'] ?: $row['full_name']); ?></td>
          <td><?= htmlspecialchars($row['role']); ?></td>
          <td><?= htmlspecialchars($row['interview_type']); ?></td>
          <td><?= htmlspecialchars($row['interviewer']); ?></td>
          <td><?= htmlspecialchars($row['location']); ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No upcoming interviews.</p>
  <?php endif; ?>
</section>

</div>

<script>
// Auto-fill role when candidate selected
const candidateSelect = document.querySelector('select[name="application_id"]');
const roleInput = document.getElementById('roleInput');
if (candidateSelect && roleInput) {
  candidateSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    roleInput.value = selected.getAttribute('data-role') || '';
  });
  // Initialize on load
  const initOpt = candidateSelect.options[candidateSelect.selectedIndex];
  if (initOpt) roleInput.value = initOpt.getAttribute('data-role') || '';
}
</script>

</body>
</html>