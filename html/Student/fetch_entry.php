<?php
require_once "studentdbconnection.php";

$id = intval($_GET['id']);

$entry = $conn->query("
    SELECT * FROM logbook_entries WHERE id = $id
")->fetch_assoc();

$tasks = $conn->query("
    SELECT task_text FROM logbook_tasks WHERE entry_id = $id
");

$learnings = $conn->query("
    SELECT learning_text FROM logbook_learnings WHERE entry_id = $id
");
?>

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