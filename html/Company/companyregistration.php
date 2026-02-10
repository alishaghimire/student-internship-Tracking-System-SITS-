<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "dbconnectcompany.php";
    $err = [];

    // Company Name
    if (isset($_POST['company_name']) && !empty(trim($_POST['company_name']))) {
        $company_name = trim($_POST['company_name']);
        if (!preg_match("/^[A-Z][a-zA-Z0-9\s&.\-']*$/", $company_name)) {
            $err['company_name'] = "Company name must start with a capital letter.<br />";
        }
    } else {
        $err['company_name'] = "Enter your company name.<br />";
    }

    // Industry
    if (isset($_POST['industry']) && !empty($_POST['industry'])) {
        $industry = $_POST['industry'];
    } else {
        $err['industry'] = "Select an industry.<br />";
    }

    // Registration Number
    if (isset($_POST['registration_number']) && !empty(trim($_POST['registration_number']))) {
        $registration_number = trim($_POST['registration_number']);
        if (!preg_match("/^[A-Z0-9]{5,}$/", $registration_number)) {
            $err['registration_number'] = "Invalid registration number format.<br />";
        }
    } else {
        $err['registration_number'] = "Enter registration number.<br />";
    }

    // Tax ID
    if (isset($_POST['tax_id']) && !empty(trim($_POST['tax_id']))) {
        $tax_id = trim($_POST['tax_id']);
        if (!preg_match("/^[A-Z0-9]{5,}$/", $tax_id)) {
            $err['tax_id'] = "Invalid Tax/VAT number.<br />";
        }
    } else {
        $err['tax_id'] = "Enter Tax/VAT number.<br />";
    }

    // Established Year
    if (isset($_POST['established_year']) && !empty($_POST['established_year'])) {
        $established_year = $_POST['established_year'];
        if ($established_year < 1900 || $established_year > date("Y")) {
            $err['established_year'] = "Enter a valid year.<br />";
        }
    } else {
        $err['established_year'] = "Enter established year.<br />";
    }

    // Number of Employees
    if (isset($_POST['num_employees']) && !empty($_POST['num_employees'])) {
        $num_employees = $_POST['num_employees'];
    } else {
        $err['num_employees'] = "Select number of employees.<br />";
    }

    // Email
    if (isset($_POST['email']) && !empty(trim($_POST['email']))) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err['email'] = "Enter a valid email.<br />";
        }
    } else {
        $err['email'] = "Enter email.<br />";
    }

    // Phone
    if (isset($_POST['phone']) && !empty(trim($_POST['phone']))) {
        $phone = trim($_POST['phone']);
        if (!preg_match("/^\+?[0-9\s\-()]{7,}$/", $phone)) {
            $err['phone'] = "Enter a valid phone number.<br />";
        }
    } else {
        $err['phone'] = "Enter phone number.<br />";
    }

    // City, State
    if (isset($_POST['city_state']) && !empty(trim($_POST['city_state']))) {
        $city_state = trim($_POST['city_state']);
    } else {
        $err['city_state'] = "Enter city and state.<br />";
    }

    // Password
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            $err['password'] = "Password must be at least 8 characters.<br />";
        }
    } else {
        $err['password'] = "Enter password.<br />";
    }

    // Confirm Password
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = $_POST['confirm_password'];
        if ($confirm_password !== $password) {
            $err['confirm_password'] = "Passwords do not match.<br />";
        }
    } else {
        $err['confirm_password'] = "Confirm your password.<br />";
    }

    // Terms
    if (!isset($_POST['terms'])) {
        $err['terms'] = "You must agree to the terms.<br />";
    }

    // If no errors → Insert into database
    if (count($err) == 0) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // MySQLi uses ? placeholders, NOT :name
        $sql = "INSERT INTO company_registration 
                (company_name, industry, registration_number, tax_id, established_year, 
                 num_employees, email, phone, city_state, password, status, submitted_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters (10 values)
        $stmt->bind_param(
            "ssssisssss",
            $company_name,
            $industry,
            $registration_number,
            $tax_id,
            $established_year,
            $num_employees,
            $email,
            $phone,
            $city_state,
            $hashed_password
        );

        if ($stmt->execute()) {

            session_start();
            $_SESSION['company_id'] = $stmt->insert_id;

            header("Location: statuscompany.php");
            exit;

        } else {
            echo "<p style='color:red;'>Database Error: " . $stmt->error . "</p>";
        }
    }
session_start();

if (isset($_SESSION['company_id'])) {
    header("Location: statuscompany.php");
    exit();
}


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration</title>
    <link rel="stylesheet" href="/project/css/Company/companyregistration.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-building icon"></i> Company Registration</h2>
        <p>Register your company to get started</p>
    </div>

    <form method="POST">

        <!-- Company Name -->
        <div class="form-group">
            <label for="company-name">Company Name *</label>
            <input type="text" name="company_name" id="company-name"
                   value="<?php echo isset($company_name) ? $company_name : ''; ?>">
            <?php echo isset($err['company_name']) ? $err['company_name'] : ''; ?>
        </div>

        <!-- Industry -->
        <div class="form-group">
            <label for="industry">Industry *</label>
            <select name="industry" id="industry">
                <option value="">Select Industry</option>
                <option value="tech" <?php if(isset($industry) && $industry=='tech') echo 'selected'; ?>>Technology</option>
                <option value="finance" <?php if(isset($industry) && $industry=='finance') echo 'selected'; ?>>Finance</option>
                <option value="health" <?php if(isset($industry) && $industry=='health') echo 'selected'; ?>>Healthcare</option>
                <option value="consumer" <?php if(isset($industry) && $industry=='Telecom') echo 'selected'; ?>>Telecommunications</option>
                <option value="arline" <?php if(isset($industry) && $industry=='consumer') echo 'selected'; ?>>Consumer Goods</option>
                <option value="Telecom" <?php if(isset($industry) && $industry=='arline') echo 'selected'; ?>>Aviation</option>




            </select>
            <?php echo isset($err['industry']) ? $err['industry'] : ''; ?>
        </div>

        <!-- Registration Number -->
        <div class="form-group">
            <label for="registration-number">Registration Number *</label>
            <input type="text" name="registration_number" id="registration-number"
                   value="<?php echo isset($registration_number) ? $registration_number : ''; ?>">
            <?php echo isset($err['registration_number']) ? $err['registration_number'] : ''; ?>
        </div>

        <!-- Tax ID -->
        <div class="form-group">
            <label for="tax-id">Tax ID / VAT Number *</label>
            <input type="text" name="tax_id" id="tax-id"
                   value="<?php echo isset($tax_id) ? $tax_id : ''; ?>">
            <?php echo isset($err['tax_id']) ? $err['tax_id'] : ''; ?>
        </div>

        <!-- Established Year -->
        <div class="form-group">
            <label for="established-year">Established Year *</label>
            <input type="number" name="established_year" id="established-year"
                   value="<?php echo isset($established_year) ? $established_year : ''; ?>">
            <?php echo isset($err['established_year']) ? $err['established_year'] : ''; ?>
        </div>

        <!-- Number of Employees -->
        <div class="form-group">
            <label for="num-employees">Number of Employees *</label>
            <select name="num_employees" id="num-employees">
                <option value="">Select Range</option>
                <option value="1-10" <?php if(isset($num_employees) && $num_employees=='1-10') echo 'selected'; ?>>1-10</option>
                <option value="11-50" <?php if(isset($num_employees) && $num_employees=='11-50') echo 'selected'; ?>>11-50</option>
                <option value="51-200" <?php if(isset($num_employees) && $num_employees=='51-200') echo 'selected'; ?>>51-200</option>
            </select>
            <?php echo isset($err['num_employees']) ? $err['num_employees'] : ''; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email"
                   value="<?php echo isset($email) ? $email : ''; ?>">
            <?php echo isset($err['email']) ? $err['email'] : ''; ?>
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone">Phone *</label>
            <input type="tel" name="phone" id="phone"
                   value="<?php echo isset($phone) ? $phone : ''; ?>">
            <?php echo isset($err['phone']) ? $err['phone'] : ''; ?>
        </div>

        <!-- City, State -->
        <div class="form-group">
            <label for="city-state">City, State *</label>
            <input type="text" name="city_state" id="city-state"
                   value="<?php echo isset($city_state) ? $city_state : ''; ?>">
            <?php echo isset($err['city_state']) ? $err['city_state'] : ''; ?>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" name="password" id="password">
            <?php echo isset($err['password']) ? $err['password'] : ''; ?>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirm-password">Confirm Password *</label>
            <input type="password" name="confirm_password" id="confirm-password">
            <?php echo isset($err['confirm_password']) ? $err['confirm_password'] : ''; ?>
        </div>

        <!-- Terms -->
       <div class="form-group terms">
    <input type="checkbox" name="terms" id="terms">
    <label for="terms">I agree to the Terms of Service and Privacy Policy</label>
</div>

<?php echo isset($err['terms']) ? "<span class='error'>{$err['terms']}</span>" : ""; ?>

        <button type="submit" class="button">Register Company</button>
    </form>

</div>
<a href="statuscompany.php" class="button">Check Registration Status</a>
</body>
</html>