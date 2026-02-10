<?php
session_start();
require_once "studentdbconnection.php";

// If student is not logged in
if (!isset($_SESSION['student_id'])) {
    die("Error: You must be logged in to view your logbook.");
}

$student_id = $_SESSION['student_id'];

// Month filter
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : "";

// Fetch entries based on login + month
if ($selectedMonth) {
    $entries = $conn->query("
        SELECT * FROM logbook_entries
        WHERE student_id = $student_id
        AND DATE_FORMAT(entry_date, '%Y-%m') = '$selectedMonth'
        ORDER BY entry_date DESC
    ");
} else {
    $entries = $conn->query("
        SELECT * FROM logbook_entries
        WHERE student_id = $student_id
        ORDER BY entry_date DESC
    ");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My LogBook</title>
  <link rel="stylesheet" href="/project/css/Student/logbook.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>.btn-feedback {
    display: inline-block;
    padding: 10px 20px;
    background: linear-gradient(90deg, #4361ee, #7209b7); /* nice gradient */
    color: #fff;
    font-weight: bold;
    text-decoration: none;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    font-family: Arial, sans-serif;
}

.btn-feedback:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    background: linear-gradient(90deg, #7209b7, #4361ee); /* reverse gradient on hover */
}
</style>
</head>

<body>

<header>
    <h1>My LogBook</h1>

    <div class="summary">
      <div class="box total"><i class="fas fa-book"></i> Total Entries</div>
      <div class="box hours"><i class="fas fa-clock"></i> Total Hours</div>
      <div class="box month"><i class="fas fa-calendar-alt"></i> This Month</div>
      <div class="box month-hours"><i class="fas fa-hourglass-half"></i> Hours This Month</div>
    </div>
</header>

<section class="filter-bar">
    <label for="month">Filter by Month:</label>

    <select id="month" onchange="window.location='mylogbook.php?month=' + this.value;">
        <option value="">-- Select Month --</option>

        <?php
        $currentYear = date("Y");

        for ($m = 1; $m <= 12; $m++) {
            $value = $currentYear . "-" . str_pad($m, 2, "0", STR_PAD_LEFT);
            $label = date("F Y", strtotime($value . "-01"));
            $selected = ($value == $selectedMonth) ? "selected" : "";
            echo "<option value='$value' $selected>$label</option>";
        }
        ?>
    </select>

    <div class="filter-actions">
      <button onclick="window.location.href='logbookentry.php'">Add Entry</button>
      
    </div>
</section>

<?php
// If no entries
if ($entries->num_rows == 0):
?>
    <section class="log-entry">
        <h2>No entries yet</h2>
        <p>Click "Add Entry" to create your first logbook entry.</p>
    </section>

<?php
// If entries exist
else:
    while ($entry = $entries->fetch_assoc()):
        $entry_id = $entry['entry_id'];

        // Fetch tasks
        $tasks = $conn->query("
            SELECT task_text FROM logbook_tasks 
            WHERE entry_id = $entry_id
        ");

        // Fetch learnings
        $learnings = $conn->query("
            SELECT learning_text FROM logbook_learnings 
            WHERE entry_id = $entry_id
        ");
?>
<section class="log-entry">
 <div style="text-align:right; margin-top:-10px;">
        <?php if ($entry['approval_status'] === 'approved'): ?>
            <span class="status completed" style="background-color:lightblue;">Approved</span>
        <?php else: ?>
            <span class="status pending">Pending</span>
        <?php endif; ?>
    </div>
    <h2><?php echo $entry['title']; ?></h2>
    <section class="log-entry">

    <h2><?php echo $entry['title']; ?></h2>

    <p class="meta">
        <?php echo date("l, F j, Y", strtotime($entry['entry_date'])); ?>
        — <?php echo $entry['hours']; ?> hours
    </p>

    <p class="location">
        <?php echo $entry['location']; ?> | Supervisor: <?php echo $entry['supervisor']; ?>
    </p>

    <div class="section">
        <h3>Tasks Completed</h3>
        <ul>
            <?php while ($t = $tasks->fetch_assoc()): ?>
                <li><?php echo $t['task_text']; ?></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="section">
        <h3>Key Learnings</h3>
        <ul>
            <?php while ($l = $learnings->fetch_assoc()): ?>
                <li><?php echo $l['learning_text']; ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
<a href="view_feedback.php" class="btn-feedback">View Feedback</a>
</section>

<?php endwhile; endif; ?>

</body>
</html>