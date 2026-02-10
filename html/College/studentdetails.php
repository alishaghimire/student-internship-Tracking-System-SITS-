<?php
require_once "collegedb_connection.php";
session_start();

if (!isset($_SESSION['college_id'])) {
    die("Unauthorized Access");
}
$college_id = $_SESSION['college_id'];

$sql = "SELECT student_id, full_name, email, status 
        FROM students_registrtaion 
        WHERE college_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $college_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Approval</title>

<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 40px;
    }

    h2 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 25px;
        font-size: 28px;
        position: relative;
    }

    /* Back arrow button */
    .back-btn {
        position: absolute;
        left: 20px;
        top: 0;
        font-size: 24px;
        text-decoration: none;
        color: #1e3a8a;
        font-weight: bold;
    }
    .back-btn:hover {
        color: #2563eb;
    }

    .table-container {
        width: 90%;
        margin: auto;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 16px;
    }

    th {
        background: #1e3a8a;
        color: white;
        padding: 14px;
        text-align: left;
        font-weight: 600;
    }

    td {
        padding: 14px;
        border-bottom: 1px solid #e5e7eb;
    }

    tr:hover {
        background: #f9fafb;
    }

    .action-btn {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-weight: 600;
        text-align: center;
        display: block;
    }

    .approve-btn { background: #2563eb; }
    .reject-btn { background: #dc2626; }

    .action-btn:hover { opacity: 0.85; }

    .accepted-text { color: green; font-weight: 600; }
    .rejected-text { color: red; font-weight: 600; }
</style>

</head>
<body>

<h2>
  <a href="collage.php" class="back-btn">&#8592;</a>  <!-- Back arrow -->
  Students of Your College
</h2>

<div class="table-container">
<table>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
    <th>View Details</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { 
    $status = strtolower(trim($row['status']));
?>
<tr>
    <td><?= htmlspecialchars($row['full_name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>

    <td>
        <?php if ($status == 'pending') { ?>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a class="action-btn approve-btn" 
                   href="approvestatus_student.php?id=<?= $row['student_id'] ?>">
                   Accept
                </a>
                <a class="action-btn reject-btn" 
                   href="reject_student.php?id=<?= $row['student_id'] ?>">
                   Reject
                </a>
            </div>
        <?php } elseif ($status == 'approved') { ?>
            <span class="accepted-text">Accepted</span>
        <?php } elseif ($status == 'rejected') { ?>
            <span class="rejected-text">Rejected</span>
        <?php } ?>
    </td>

    <td>
      <a href="viewstudentdetails.php?id=<?= $row['student_id']; ?>" class="btn view">View</a>
    </td>
</tr>
<?php } ?>

</table>
</div>

</body>
</html>