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
$stmt = $conn->prepare("SELECT * FROM contact_details WHERE student_id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

/* Pre-fill values */
$email       = $data['email']       ?? ($_POST['email'] ?? '');
$phone       = $data['phone']       ?? ($_POST['phone'] ?? '');
$altPhone    = $data['altPhone']    ?? ($_POST['altPhone'] ?? '');
$country     = $data['country']     ?? ($_POST['country'] ?? '');
$state       = $data['state']       ?? ($_POST['state'] ?? '');
$city        = $data['city']        ?? ($_POST['city'] ?? '');
$fullAddress = $data['fullAddress'] ?? ($_POST['fullAddress'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* VALIDATION */
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $err['email'] = "Enter a valid email.";

    if ($phone === '' || !preg_match("/^[0-9+\-\s]{7,20}$/", $phone))
        $err['phone'] = "Enter a valid phone number.";

    if ($country === '')     $err['country'] = "Enter your country.";
    if ($state === '')       $err['state'] = "Enter your state.";
    if ($city === '')        $err['city'] = "Enter your city.";
    if ($fullAddress === '') $err['fullAddress'] = "Enter your full address.";

    if (empty($err)) {

        /* If data exists → UPDATE */
        if ($data) {

            $stmt = $conn->prepare("
                UPDATE contact_details SET
                    email=?, phone=?, altPhone=?, country=?, state=?, city=?, fullAddress=?
                WHERE student_id=?
            ");

            $stmt->bind_param(
                "sssssssi",
                $email, $phone, $altPhone,
                $country, $state, $city, $fullAddress,
                $student_id
            );

            $stmt->execute();
        }

        /* If no data → INSERT */
        else {

            $stmt = $conn->prepare("
                INSERT INTO contact_details
                (student_id, email, phone, altPhone, country, state, city, fullAddress)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "isssssss",
                $student_id, $email, $phone, $altPhone,
                $country, $state, $city, $fullAddress
            );

            $stmt->execute();
        }

        /* FIXED REDIRECT — correct spelling */
        header("Location: education.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Details</title>
<link rel="stylesheet" href="/project/css/Student/contractdetails.css">
</head>

<body>

<div class="form-container">
<h2 class="form-title">Contact Details</h2>

<form id="contactForm" method="POST">

    <div class="form-row">
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error"><?php echo $err['email'] ?? ''; ?></span>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            <span class="error"><?php echo $err['phone'] ?? ''; ?></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="altPhone">Alternate Phone</label>
            <input type="text" id="altPhone" name="altPhone" value="<?php echo htmlspecialchars($altPhone); ?>">
        </div>

        <div class="form-group">
            <label for="country">Country *</label>
            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>">
            <span class="error"><?php echo $err['country'] ?? ''; ?></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="state">State *</label>
            <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($state); ?>">
            <span class="error"><?php echo $err['state'] ?? ''; ?></span>
        </div>

        <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>">
            <span class="error"><?php echo $err['city'] ?? ''; ?></span>
        </div>
    </div>

    <div class="form-group full-width">
        <label for="fullAddress">Full Address *</label>
        <textarea id="fullAddress" name="fullAddress" rows="3"><?php echo htmlspecialchars($fullAddress); ?></textarea>
        <span class="error"><?php echo $err['fullAddress'] ?? ''; ?></span>
    </div>

    <button type="button" class="back-btn" onclick="window.location='personal.php'">Back</button>
    <button type="submit" class="submit-btn">Next</button>

</form>
</div>

</body>
</html>