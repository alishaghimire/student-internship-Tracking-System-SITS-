<?php
session_start();
require_once "studentdbconnection.php";

// If student is not logged in
if (!isset($_SESSION['student_id'])) {
    die("Error: You must be logged in to add a logbook entry.");
}

$student_id = $_SESSION['student_id'];

// ✅ Fetch student name from DB (fixed table name + assign to $full_name)
$studentQuery = $conn->prepare("SELECT full_name FROM students_registrtaion WHERE student_id = ?");
$studentQuery->bind_param("i", $student_id);
$studentQuery->execute();
$studentResult = $studentQuery->get_result()->fetch_assoc();

$full_name = $studentResult['full_name'];   // used in INSERT
$name = $studentResult['full_name'];        // used for display in disabled input

$err = [];
$title = $date = $hours = $location = $supervisor = "";
$tasks = $learnings = [];

// -------------------------------
// Handle form submit
// -------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Title
    if (!empty($_POST['title']) && trim($_POST['title'])) {
        $title = trim($_POST['title']);
        if (!preg_match("/^[A-Z][a-zA-Z0-9\s.'-]*$/", $title)) {
            $err['title'] = "Title must start with a capital letter.<br />";
        }
    } else {
        $err['title'] = "Enter the title.<br />";
    }

    // Date
    if (!empty($_POST['date']) && trim($_POST['date'])) {
        $date = $_POST['date'];
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            $err['date'] = "Enter a valid date.<br />";
        }
    } else {
        $err['date'] = "Select a date.<br />";
    }

    // Hours
    if (!empty($_POST['hours']) && trim($_POST['hours'])) {
        $hours = trim($_POST['hours']);
        if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $hours) || $hours <= 0) {
            $err['hours'] = "Hours must be a positive number.<br />";
        }
    } else {
        $err['hours'] = "Enter the number of hours.<br />";
    }

    // Location
    if (!empty($_POST['location']) && trim($_POST['location'])) {
        $location = trim($_POST['location']);
        if (!preg_match("/^[A-Z][a-zA-Z0-9\s.'-]*$/", $location)) {
            $err['location'] = "Location must start with a capital letter.<br />";
        }
    } else {
        $err['location'] = "Enter the location.<br />";
    }

    // Supervisor
    if (!empty($_POST['supervisor']) && trim($_POST['supervisor'])) {
        $supervisor = trim($_POST['supervisor']);
        if (!preg_match("/^[A-Z][a-zA-Z\s.'-]*$/", $supervisor)) {
            $err['supervisor'] = "Supervisor name must start with a capital letter.<br />";
        }
    } else {
        $err['supervisor'] = "Enter the supervisor's name.<br />";
    }

    // Tasks
    if (!empty($_POST['tasks'])) {
        $tasks = array_map('trim', $_POST['tasks']);
        $validTasks = array_filter($tasks, fn($t) => $t !== "");
        if (count($validTasks) === 0) {
            $err['tasks'] = "Enter at least one task.<br />";
        }
    } else {
        $err['tasks'] = "Enter at least one task.<br />";
    }

    // Learnings
    if (!empty($_POST['learnings'])) {
        $learnings = array_map('trim', $_POST['learnings']);
        $validLearnings = array_filter($learnings, fn($l) => $l !== "");
        if (count($validLearnings) === 0) {
            $err['learnings'] = "Enter at least one learning.<br />";
        }
    } else {
        $err['learnings'] = "Enter at least one learning.<br />";
    }

    // If no errors → Insert into DB
    if (empty($err)) {

        $stmt = $conn->prepare("
           INSERT INTO logbook_entries (student_id, full_name, title, entry_date, hours, location, supervisor)
           VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

      $stmt->bind_param(
    "isssdss",
    $student_id,
    $full_name,
    $title,
    $date,
    $hours,
    $location,
    $supervisor
);

        $stmt->execute();
        $entry_id = $stmt->insert_id;
        $stmt->close();

        // Insert tasks
        $stmtTask = $conn->prepare("
            INSERT INTO logbook_tasks (entry_id, task_text)
            VALUES (?, ?)
        ");
        foreach ($validTasks as $task) {
            $stmtTask->bind_param("is", $entry_id, $task);
            $stmtTask->execute();
        }
        $stmtTask->close();

        // Insert learnings
        $stmtLearn = $conn->prepare("
            INSERT INTO logbook_learnings (entry_id, learning_text)
            VALUES (?, ?)
        ");
        foreach ($validLearnings as $learning) {
            $stmtLearn->bind_param("is", $entry_id, $learning);
            $stmtLearn->execute();
        }
        $stmtLearn->close();

        // Redirect back to logbook
        echo "<script>alert('Logbook entry saved successfully!'); 
        window.location.href='mylogbook.php';</script>";
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>New Logbook Entry</title>
  <link rel="stylesheet" href="/project/css/Student/lockbookentry.css">
</head>

<body>
    <a href="mylogbook.php">Back</a>

  <div class="page">
    <div class="card">

      <h2>Add new logbook entry</h2>
      <form action="" method="post">

<label for="name">Student Name:</label>
<input type="text" id="name" value="<?php echo $name; ?>" disabled>


        <!-- name -->
        <label for="title">Title *</label>
        <input type="text" id="title" name="title"
               placeholder="e.g., React Component Development"
               value="<?php echo $title; ?>">
        <?php echo $err['title'] ?? ''; ?>

        <!-- DATE -->
        <label for="date">Date *</label>
        <input type="date" id="date" name="date"
               value="<?php echo $date; ?>">
        <?php echo $err['date'] ?? ''; ?>

        <!-- HOURS -->
        <label for="hours">Hours *</label>
        <input type="number" id="hours" name="hours" min="0" step="0.5"
               placeholder="e.g., 8"
               value="<?php echo $hours; ?>">
        <?php echo $err['hours'] ?? ''; ?>

        <!-- LOCATION -->
        <label for="location">Location *</label>
        <input type="text" id="location" name="location"
               placeholder="e.g., Creative Studio"
               value="<?php echo $location; ?>">
        <?php echo $err['location'] ?? ''; ?>

        <!-- SUPERVISOR -->
        <label for="supervisor">Supervisor *</label>
        <input type="text" id="supervisor" name="supervisor"
               placeholder="e.g., Sarah Johnson"
               value="<?php echo $supervisor; ?>">
        <?php echo $err['supervisor'] ?? ''; ?>

        <!-- TASKS -->
        <label>Tasks Completed *</label>
        <div id="task-list">
            <?php
            if (!empty($tasks)) {
                foreach ($tasks as $task) {
                    echo '<input type="text" name="tasks[]" class="task-input" value="'.$task.'">';
                }
            } else {
                echo '<input type="text" name="tasks[]" class="task-input" placeholder="Describe a task you completed">';
            }
            ?>
        </div>
        <?php echo $err['tasks'] ?? ''; ?>
        <button type="button" id="add-task-btn">+ Add task</button>

        <!-- LEARNINGS -->
        <label>Key Learnings *</label>
        <div id="learning-list">
            <?php
            if (!empty($learnings)) {
                foreach ($learnings as $learning) {
                    echo '<input type="text" name="learnings[]" class="task-input" value="'.$learning.'">';
                }
            } else {
                echo '<input type="text" name="learnings[]" class="task-input" placeholder="Describe something you learned">';
            }
            ?>
        </div>
        <?php echo $err['learnings'] ?? ''; ?>
        <button type="button" id="add-learning-btn">+ Add learning</button>

        <button type="submit" class="btn btn-primary">Save entry</button>

      </form>
    </div>
  </div>

<script>
// Add Task
document.querySelector("#add-task-btn").addEventListener("click", () => {
    const div = document.createElement("div");
    div.innerHTML = `<input type="text" name="tasks[]" class="task-input"
                     placeholder="Describe a task you completed">`;
    document.querySelector("#task-list").appendChild(div);
});

// Add Learning
document.querySelector("#add-learning-btn").addEventListener("click", () => {
    const div = document.createElement("div");
    div.innerHTML = `<input type="text" name="learnings[]" class="task-input"
                     placeholder="Describe something you learned">`;
    document.querySelector("#learning-list").appendChild(div);
});
</script>

</body>
</html>