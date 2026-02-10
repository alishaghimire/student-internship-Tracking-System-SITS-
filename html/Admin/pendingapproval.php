<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Approvals</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/SITS/Css/Admin/pendingapproval.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<section class="page-title">
  <h2>Pending Approvals</h2>
  <p>Review and approve company internship postings</p>

  <!-- FILTER BY TYPE -->
  <div class="filters">
    <button class="filter-btn active" id="btn-all">All</button>
    <button class="filter-btn" id="btn-companies">Companies</button>
    <button class="filter-btn" id="btn-colleges">Colleges</button>
  </div>

  <!-- Section where content loads -->
  <div id="approval-section"></div>
</section>

<script>
function activateButton(btn) {
  document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
  btn.classList.add("active");
}

// Load Companies only
$("#btn-companies").on("click", function () {
  activateButton(this);
  $("#approval-section").load("companypending.php");
});

// Load Colleges only
$("#btn-colleges").on("click", function () {
  activateButton(this);
  $("#approval-section").load("collegeapproval.php");
});

// Load All (Companies + Colleges together)
$("#btn-all").on("click", function () {
  activateButton(this);
  $("#approval-section").html(""); // clear first

  // Load companies and append
  $.get("companypending.php", function (data) {
    $("#approval-section").append(data);
  });

  // Load colleges and append
  $.get("collegeapproval.php", function (data) {
    $("#approval-section").append(data);
  });
});

// Trigger "All" automatically when page loads
$(document).ready(function() {
  $("#btn-all").trigger("click");
});
</script>

</body>
</html>