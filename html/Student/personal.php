<?php
session_start();
require_once "studentdbconnection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: studentLogin.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$err = [];

/* FETCH EXISTING DATA */
$stmt = $conn->prepare("SELECT * FROM student_profile WHERE student_id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

/* If form submitted */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* -------------------------------
       PROFILE PICTURE
    --------------------------------*/
    if (!empty($_FILES['profilePic']['name'])) {

        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $err['profilePic'] = "Only JPG, JPEG, PNG, or GIF images are allowed.<br>";
        }

        if ($_FILES['profilePic']['size'] > 2 * 1024 * 1024) {
            $err['profilePic'] = "Image must be less than 2MB.<br>";
        }

    } else {
        if (!$data) { // Only required for NEW users
            $err['profilePic'] = "Upload your profile picture.<br>";
        }
    }

    /* -------------------------------
       FIRST NAME
    --------------------------------*/
    if (!empty($_POST['fname']) && trim($_POST['fname'])) {
        $fname = trim($_POST['fname']);
        if (!preg_match("/^[A-Z][a-zA-Z\s.'-]*$/", $fname)) {
            $err['fname'] = "First name must start with a capital letter and contain only letters.<br>";
        }
    } else {
        $err['fname'] = "Enter your first name.<br>";
    }

    /* -------------------------------
       LAST NAME
    --------------------------------*/
    if (!empty($_POST['lname']) && trim($_POST['lname'])) {
        $lname = trim($_POST['lname']);
        if (!preg_match("/^[A-Z][a-zA-Z\s.'-]*$/", $lname)) {
            $err['lname'] = "Last name must start with a capital letter and contain only letters.<br>";
        }
    } else {
        $err['lname'] = "Enter your last name.<br>";
    }

    /* -------------------------------
       DATE OF BIRTH
    --------------------------------*/
    if (!empty($_POST['dob'])) {
        $dob = $_POST['dob'];
        if (strtotime($dob) > time()) {
            $err['dob'] = "Date of birth cannot be in the future.<br>";
        }
    } else {
        $err['dob'] = "Select your date of birth.<br>";
    }

    /* -------------------------------
       GENDER
    --------------------------------*/
    if (!empty($_POST['gender'])) {
        $gender = $_POST['gender'];
    } else {
        $err['gender'] = "Select your gender.<br>";
    }

    /* -------------------------------
       NATIONALITY
    --------------------------------*/
    if (!empty($_POST['nationality']) && trim($_POST['nationality'])) {
        $nationality = trim($_POST['nationality']);
        if (!preg_match("/^[A-Z][a-zA-Z\s.'-]*$/", $nationality)) {
            $err['nationality'] = "Nationality must start with a capital letter and contain only letters.<br>";
        }
    } else {
        $err['nationality'] = "Enter your nationality.<br>";
    }

    /* -------------------------------
       MARITAL STATUS
    --------------------------------*/
    if (!empty($_POST['marital'])) {
        $marital = $_POST['marital'];
    } else {
        $err['marital'] = "Select your marital status.<br>";
    }

    /* -------------------------------
       IF NO ERRORS → INSERT OR UPDATE
    --------------------------------*/
    if (empty($err)) {

        /* Upload profile picture */
        $profilePicName = $data['profilePic'] ?? null;

        if (!empty($_FILES['profilePic']['name'])) {
            $profilePicName = time() . "_" . basename($_FILES['profilePic']['name']);
            $uploadPath = __DIR__ . "/uploads/" . $profilePicName;
            move_uploaded_file($_FILES['profilePic']['tmp_name'], $uploadPath);
        }

        /* If data exists → UPDATE */
        if ($data) {

            $stmt = $conn->prepare("
                UPDATE student_profile SET
                    profilePic=?, fname=?, lname=?, dob=?, gender=?, nationality=?, marital=?
                WHERE student_id=?
            ");

            $stmt->bind_param(
                "sssssssi",
                $profilePicName, $fname, $lname, $dob,
                $gender, $nationality, $marital,
                $student_id
            );

            $stmt->execute();
            header("Location: contractdetails.php");
            exit;
        }

        /* If no data → INSERT */
        else {

            $stmt = $conn->prepare("
                INSERT INTO student_profile
                (student_id, profilePic, fname, lname, dob, gender, nationality, marital)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "isssssss",
                $student_id, $profilePicName, $fname, $lname, $dob,
                $gender, $nationality, $marital
            );

            $stmt->execute();
            header("Location: contractdetails.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Details</title>
    <link rel="stylesheet" href="/project/css/Student/personal.css">
</head>
<body>

<form id="personalForm" method="POST" enctype="multipart/form-data">

    <div class="profile-pic-section">
        <h3>Profile Picture</h3>

        <div class="profile-pic-wrapper">
            <label for="profilePic">Profile Picture *</label>
            <input type="file" name="profilePic" id="profilePic">
            <?php echo $err['profilePic'] ?? ''; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="fname">First Name *</label>
            <input type="text" name="fname" id="fname"
                   value="<?php echo $data['fname'] ?? $fname ?? ''; ?>">
            <?php echo $err['fname'] ?? ''; ?>
        </div>

        <div class="form-group">
            <label for="lname">Last Name *</label>
            <input type="text" name="lname" id="lname"
                   value="<?php echo $data['lname'] ?? $lname ?? ''; ?>">
            <?php echo $err['lname'] ?? ''; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="dob">Date of Birth *</label>
            <input type="date" name="dob" id="dob"
                   value="<?php echo $data['dob'] ?? $dob ?? ''; ?>">
            <?php echo $err['dob'] ?? ''; ?>
        </div>

        <div class="form-group">
            <label for="gender">Gender *</label>
            <select name="gender" id="gender">
                <option value="">Select gender</option>
                <option value="Male"   <?php if(($data['gender'] ?? $gender ?? '')=="Male") echo "selected"; ?>>Male</option>
                <option value="Female" <?php if(($data['gender'] ?? $gender ?? '')=="Female") echo "selected"; ?>>Female</option>
                <option value="Other"  <?php if(($data['gender'] ?? $gender ?? '')=="Other") echo "selected"; ?>>Other</option>
            </select>
            <?php echo $err['gender'] ?? ''; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="nationality">Nationality *</label>
            <input type="text" name="nationality" id="nationality"
                   value="<?php echo $data['nationality'] ?? $nationality ?? ''; ?>">
            <?php echo $err['nationality'] ?? ''; ?>
        </div>

        <div class="form-group">
            <label for="marital">Marital Status *</label>
            <select name="marital" id="marital">
                <option value="">Select status</option>
                <option value="Single" <?php if(($data['marital'] ?? $marital ?? '')=="Single") echo "selected"; ?>>Single</option>
                <option value="Married" <?php if(($data['marital'] ?? $marital ?? '')=="Married") echo "selected"; ?>>Married</option>
            </select>
            <?php echo $err['marital'] ?? ''; ?>
        </div>
    </div>

    <button type="submit" class="submit-btn">Next</button>
</form>

</body>
</html>