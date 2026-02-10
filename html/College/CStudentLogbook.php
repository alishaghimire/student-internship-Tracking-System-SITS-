<?php
session_start();
require_once "collegedb_connection.php";

if (!isset($_SESSION['college_id'])) {
    die("Error: You must be logged in.");
}
$college_id = $_SESSION['college_id'];

// --- Stats Queries ---
$totalQuery = $conn->prepare("
    SELECT COUNT(*) AS total, 
           SUM(CASE WHEN approval_status='approved' THEN 1 ELSE 0 END) AS approved,
           SUM(CASE WHEN approval_status!='approved' THEN 1 ELSE 0 END) AS inprogress,
           COALESCE(SUM(hours),0) AS total_hours
    FROM logbook_entries 
    WHERE student_id IN (SELECT student_id FROM students_registrtaion WHERE college_id=?)
");
$totalQuery->bind_param("i", $college_id);
$totalQuery->execute();
$stats = $totalQuery->get_result()->fetch_assoc();
$totalQuery->close();

$totalEntries = $stats['total'];
$approvedEntries = $stats['approved'];
$inProgressEntries = $stats['inprogress'];
$totalHours = $stats['total_hours'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Log Book</title>
  <style>
    /* (same CSS as before) */
    * {margin:0;padding:0;box-sizing:border-box;font-family:"Poppins",sans-serif;}
    body {background:linear-gradient(135deg,#f5f7fa,#e3e9f0);color:#333;min-height:100vh;}
    .top-nav{display:flex;justify-content:space-between;align-items:center;padding:15px 40px;background:#fff;border-bottom:1px solid #eee;box-shadow:0 2px 6px rgba(0,0,0,0.1);position:sticky;top:0;z-index:100;}
    .logo{display:flex;align-items:center;gap:12px;}
    .logo h3{font-size:1.3rem;font-weight:600;color:#2575fc;}
    .logo p{font-size:0.85rem;color:#666;}
    .btn{text-decoration:none;padding:8px 18px;border-radius:25px;font-size:0.9rem;font-weight:600;transition:all 0.3s ease;}
    .btn.home{background:linear-gradient(135deg,#6a11cb,#2575fc);color:#fff;}
    .btn.logout{background:linear-gradient(135deg,#ff512f,#dd2476);color:#fff;margin-left:10px;}
    .btn:hover{opacity:0.9;transform:translateY(-2px);}
    .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;padding:40px;}
    .stat-box{background:#fff;border-radius:16px;padding:30px;text-align:center;box-shadow:0 6px 15px rgba(0,0,0,0.08);transition:transform 0.3s ease,box-shadow 0.3s ease;}
    .stat-box:hover{transform:translateY(-8px);box-shadow:0 10px 20px rgba(0,0,0,0.15);}
    .stat-box h3{font-size:2.2rem;margin-bottom:8px;font-weight:700;}
    .stat-box p{font-size:1rem;color:#666;}
    .stat-box.green h3{color:#28a745;}
    .stat-box.blue h3{color:#007bff;}
    .stat-box.purple h3{color:#6f42c1;}
    .container{background:#fff;margin:20px 40px;border-radius:16px;padding:30px;box-shadow:0 6px 15px rgba(0,0,0,0.08);}
    .header-section h2{font-size:1.6rem;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:10px;color:#2575fc;}
    .log-list{margin-top:20px;}
    .log-entry{background:#f9fafc;border-radius:12px;padding:20px;margin-bottom:15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);transition:transform 0.3s ease;}
    .log-entry:hover{transform:translateY(-4px);}
    .log-entry h3{font-size:1.2rem;color:#333;margin-bottom:8px;}
    .log-entry p{font-size:0.9rem;color:#555;margin-bottom:4px;}
    .log-entry .btn-view{display:inline-block;margin-top:10px;background:linear-gradient(135deg,#2575fc,#6a11cb);color:#fff;padding:8px 16px;border-radius:20px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.3s ease;}
    .log-entry .btn-view:hover{background:linear-gradient(135deg,#6a11cb,#2575fc);}
  </style>
</head>
<body>

<header class="top-nav">
  <div class="logo">
    <img src="collage.png" alt="College Logo" width="50">
    <div>
      <h3>College Dashboard</h3>
      <p>Student Internship Tracking System</p>
    </div>
  </div>
  <div class="right">
    <a href="collage.php" class="btn home">Home</a>
    <a href="logout.php" class="btn logout">Logout</a>
  </div>
</header>

<div class="stats">
  <div class="stat-box">
    <h3><?= $totalEntries ?></h3>
    <p>Total Entries</p>
  </div>

  <div class="stat-box green">
    <h3><?= $approvedEntries ?></h3>
    <p>Completed</p>
  </div>

  <div class="stat-box blue">
    <h3><?= $inProgressEntries ?></h3>
    <p>In Progress</p>
  </div>

  <div class="stat-box purple">
    <h3><?= $totalHours ?></h3>
    <p>Total Hours</p>
  </div>
</div>

<div class="header-section">
  <h2><span class="book-icon"></span> Student Log Book</h2>
</div>

<div id="logList" class="log-list">
  <?php 
    $GLOBALS['college_id'] = $college_id;
    include "fetch_logbook_entries.php"; 
  ?>
</div>

<script src="CStudentLogbook.js"></script>
</body>
</html>