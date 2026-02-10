<?php
session_start();
require_once "studentdbconnection.php";

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    die("Error: You must be logged in to view feedback.");
}

$student_id = $_SESSION['student_id'];

// Fetch all applications of this student along with feedback
$sql = "
    SELECT a.created_at AS applied_on,
           r.note_text, r.created_at AS feedback_on
    FROM applications a
    LEFT JOIN review_notes r ON a.application_id = r.application_id
    WHERE a.student_id = ?
    ORDER BY a.created_at DESC, r.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Feedback</title>
    <link rel="stylesheet" href="/project/css/Student/logbook.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; margin-bottom: 30px; }
        .feedback-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .feedback-card h4 { margin: 0 0 5px; }
        .feedback-card p { margin: 5px 0; }
        small { color: gray; }
    </style>
</head>
<body>
    <h1>My Feedback</h1>

    <section class="feedback-list">
        <?php if ($result->num_rows == 0): ?>
            <p>No feedback available yet.</p>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="feedback-card">
                    <p><strong>Applied On:</strong> <?php echo date("d M Y", strtotime($row['applied_on'])); ?></p>

                    <?php if ($row['note_text']): ?>
                        <h4>Feedback:</h4>
                        <p><?php echo $row['note_text']; ?></p>
                        <?php if ($row['feedback_on']): ?>
                            <small>Received on: <?php echo date("d M Y", strtotime($row['feedback_on'])); ?></small>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>No feedback yet.</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </section>
</body>
</html>
