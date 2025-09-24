<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../css/proj/student.css" />
</head>
<body>
  <header>
    <img src="../pictures/graduation.png" alt="">
    <div class="top">
      <h1>Internship Tracking System</h1>
      <h2>Student Dashboard</h2>
    </div>
    <a href="#"><div class="profile">AU</div></a>
  </header>

  <section class="welcome">
    <p>Welcome back, <strong>Ram</strong>!</p>
  </section>

  <div class="nav-wapper">
    <nav class="dashboard-nav">
      <ul>
         <li><a href="#" class="active" id="myInternships">My Internships</a></li>
  <li><a href="#" class="active" id="applyInternships">Apply for Internships</a></li>
  <li><a href="#" class="active" id="myLogbook">My Logbook</a></li>
  <li><a href="#" class="active" id="myCV">My CV</a></li>


      </ul>
    </nav>
  </div>


  <div id="internship-content"></div>
  <div id="applyinternship-content"></div>
  <div id="mylogbook-content"></div>
  <div id="mycvbuilder"></div>


  <div class="logout">
    <img src="../pictures/logout.png" alt="photo"><a href="login.php">Logout</a>
  </div>

  <script src="../javascript/student.js">    </script>
</body>
</html>