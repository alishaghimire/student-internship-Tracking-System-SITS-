<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="student-details.css">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
         <div class="logo">
            <img src="collage.png" alt="College Logo" width="50">
            <div>
                <h2>College Dashboard</h2>
                <p>Student Internship Tracking System</p>
            </div>
        </div>
        </div>

        <div class="nav-buttons">
            <a href="collage.php" class="home-btn"><i class="ri-home-4-line"></i> Home</a>
            <a href="login.php" class="logout-btn"><i class="ri-logout-box-r-line"></i> Logout</a>
        </div>
    </header>

    <!-- MAIN CARD -->
    <div class="details-card">
        
        <div class="card-header">
            <div class="header-left">
                <i class="ri-user-line icon"></i>
                <h3>Student Details</h3>
            </div>

            <a href="edit.php" class="edit-btn"><i class="ri-edit-line"></i> Edit</a>
        </div>

        <div class="info-grid">

            <!-- LEFT SIDE -->
            <div class="info-column">

                <div class="info-box">
                    <label>Full Name</label>
                    <p>John Anderson</p>
                </div>

                <div class="info-box">
                    <label>Email</label>
                    <p><i class="ri-mail-line"></i> john.anderson@college.edu</p>
                </div>

                <div class="info-box">
                    <label>Date of Birth</label>
                    <p><i class="ri-calendar-line"></i> 15/05/1998</p>
                </div>

                <div class="info-box">
                    <label>Address</label>
                    <p><i class="ri-map-pin-line"></i> 123 Campus Drive, University City, CA 94305</p>
                </div>

                <div class="info-box">
                    <label>Current GPA</label>
                    <p class="gpa"><span>3.85</span> / 4.0</p>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="info-column">
                <div class="info-box">
                    <label>Phone</label>
                    <p><i class="ri-phone-line"></i>9812345678</p>
                </div>

                <div class="info-box">
                    <label>Enrollment Date</label>
                    <p><i class="ri-calendar-line"></i> 01/09/2024</p>
                </div>

            </div>

        </div>

    </div>

    <script src="student-details.js"></script>
</body>
</html>
