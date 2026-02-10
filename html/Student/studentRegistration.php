<?php
require_once "studentdbconnection.php";

// ---------------------------------------------
// FETCH APPROVED COLLEGES FOR DROPDOWN
// ---------------------------------------------
$collegeList = [];
$sql = "SELECT college_id, college_name FROM colleges WHERE status = 'Approved'";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $collegeList[] = $row;
}

// ---------------------------------------------
// INITIALIZE VARIABLES
// ---------------------------------------------
$err = [];
$name = "";
$student_id = "";
$email = "";
$phone = "";
$major = "";
$college = "";
$password = "";
$confirm_password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // -------------------------------
    // Full Name
    // -------------------------------
    if (!empty($_POST['name']) && trim($_POST['name'])) {
        $name = trim($_POST['name']);
        if (!preg_match("/^[A-Z][a-zA-Z\s.'-]*$/", $name)) {
            $err['name'] = "Full name must start with a capital letter and contain only letters.<br />";
        }
    } else {
        $err['name'] = "Enter your full name.<br />";
    }

    // -------------------------------
    // Student ID
    // -------------------------------
    if (!empty($_POST['student_id']) && trim($_POST['student_id'])) {
        $student_id = trim($_POST['student_id']);
        if (!preg_match("/^[A-Z][A-Za-z0-9\-]*$/", $student_id)) {
            $err['student_id'] = "Student ID must start with a capital letter.<br />";
        }
    } else {
        $err['student_id'] = "Enter your student ID.<br />";
    }

    // -------------------------------
    // Email
    // -------------------------------
    if (!empty($_POST['email']) && trim($_POST['email'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err['email'] = "Enter a valid email address.<br />";
        }
    } else {
        $err['email'] = "Enter your email.<br />";
    }

    // -------------------------------
    // Phone (Optional)
    // -------------------------------
  // -------------------------------
if (!empty($_POST['phone']) && trim($_POST['phone'])) {
    $phone = trim($_POST['phone']);
    
    // Regex: starts with 97 or 98, followed by 8 more digits (total 10 digits)
    if (!preg_match("/^(97|98)[0-9]{8}$/", $phone)) {
        $err['phone'] = "Phone number must start with 97 or 98 and be exactly 10 digits.<br />";
    }
} else {
    $err['phone'] = "Enter your phone number.<br />";
}

    // -------------------------------
    // Major
    // -------------------------------
    if (!empty($_POST['major']) && trim($_POST['major'])) {
        $major = trim($_POST['major']);
        if (!preg_match("/^[A-Z][a-zA-Z\s&.\-']*$/", $major)) {
            $err['major'] = "Major must start with a capital letter.<br />";
        }
    } else {
        $err['major'] = "Enter your major.<br />";
    }

    // -------------------------------
    // College Dropdown
    // -------------------------------
    if (!empty($_POST['college'])) {
        $college = $_POST['college'];
    } else {
        $err['college'] = "Select your college.<br />";
    }

    // -------------------------------
    // Password
    // -------------------------------
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/", $password)) {
            $err['password'] = "Password must be at least 8 characters, include uppercase, lowercase, and a number.<br />";
        }
    } else {
        $err['password'] = "Enter your password.<br />";
    }

    // -------------------------------
    // Confirm Password
    // -------------------------------
    if (!empty($_POST['confirm_password'])) {
        $confirm_password = $_POST['confirm_password'];
        if ($password !== $confirm_password) {
            $err['confirm_password'] = "Passwords do not match.<br />";
        }
    } else {
        $err['confirm_password'] = "Confirm your password.<br />";
    }

    // -------------------------------
    // Terms Checkbox
    // -------------------------------
    if (!isset($_POST['terms'])) {
        $err['terms'] = "You must agree to the terms.<br />";
    }

    // ---------------------------------------------
    // INSERT INTO DATABASE IF NO ERRORS
    // ---------------------------------------------
    if (empty($err)) {

        // Fetch college name
        $college_id = (int)$college;
        $college_name = "";
        $stmtCollege = $conn->prepare("SELECT college_name FROM colleges WHERE college_id = ?");
        $stmtCollege->bind_param("i", $college_id);
        $stmtCollege->execute();
        $resultCollege = $stmtCollege->get_result();
        if ($row = $resultCollege->fetch_assoc()) {
            $college_name = $row['college_name'];
        }
        $stmtCollege->close();

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL
        $sql = "INSERT INTO students_registrtaion
            (full_name, student_code, email, phone, major, college_id, college_name, password, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssiss",
            $name,
            $student_id,
            $email,
            $phone,
            $major,
            $college_id,
            $college_name,
            $hashedPassword
        );
 if ($stmt->execute()) {

            session_start();
            $_SESSION['student_id'] = $stmt->insert_id;

            header("Location: studentstatuspending.php");
            exit;

        } else {
            echo "<p style='color:red;'>Database Error: " . $stmt->error . "</p>";
        }
    }
session_start();

if (isset($_SESSION['student_id'])) {
    header("Location:studentstatuspending.php");
    exit();
}
     
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Registration</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="/project/css/Student/studentRegistration.css" />
</head>

<body>
  <div class="register-wrapper">
    <a href="/project/html/fristpage.php" class="back-link">← Back to home</a>

    <div class="register-box">
      <h1>Student Registration</h1>
      <p class="subtitle">Create your student account</p>

      <!-- IMPORTANT: action="" so POST returns to same PHP file -->
      <form action="" method="POST">

        <label for="name">Full Name *</label>
        <input type="text" name="name" id="name" placeholder="Enter your name."value="<?php echo isset($name) ? $name : ''; ?>">
        <?php echo isset($err['name']) ? $err['name'] : ''; ?>


        <label for="student_id">Student ID *</label>
        <input type="text" id="student_id" name="student_id" placeholder="STU123456"
               value="<?php echo isset($student_id) ? $student_id : ''; ?>">
        <?php echo isset($err['student_id']) ? $err['student_id'] : ''; ?>


        <label for="email">Email *</label>
        <input type="email" id="email" name="email" placeholder="john@student.edu"  value="<?php echo isset($email) ? $email : ''; ?>">
        <?php echo isset($err['email']) ? $err['email'] : ''; ?>



        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" placeholder="+(98/97)" value="<?php echo isset($phone) ? $phone : ''; ?>">
        <?php echo isset($err['phone']) ? $err['phone'] : ''; ?>


        <label for="major">Major *</label>
        <input type="text" id="major" name="major" placeholder="Computer Science" value="<?php echo isset($major) ? $major : ''; ?>">
        <?php echo isset($err['major']) ? $err['major'] : ''; ?>


        <!-- College Dropdown -->
<label for="college">College *</label>
   <select id="college" name="college">
    <option value="">-- Select College --</option>

    <?php foreach ($collegeList as $col): ?>
        <option value="<?= $col['college_id']; ?>"
            <?= (isset($college) && $college == $col['college_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($col['college_name']); ?>
        </option>
    <?php endforeach; ?>
</select>


<span class="error"><?php echo isset($err['college']) ? $err['college'] : ''; ?></span>


        <label for="password">Password *</label>
        <input type="password" id="password" name="password" placeholder="Min. 8 characters" value="<?php echo isset($password) ? $password : ''; ?>">
        <?php echo isset($err['password']) ? $err['password'] : ''; ?>  


        <label for="confirm_password">Confirm Password *</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" value="<?php echo isset($confirm_password) ? $confirm_password : ''; ?>">
        <?php echo isset($err['confirm_password']) ? $err['confirm_password'] : ''; ?>  


        <div class="checkbox-group">
          <input type="checkbox" id="terms" name="terms" required />
          <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
        </div>

        <button type="submit" class="register-btn">Create Account</button>
      </form>

      <p class="signin-text">
        Already have an account? <a href="login.html">Sign in</a>
      </p>
    </div>
  </div>
</body>
</html>