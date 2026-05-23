<?php
session_start();
require_once "dbconnectcompany.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: companyLogin.php");
    exit;
}

$err = [];

/* Helpers */
function esc($v) { return htmlspecialchars($v ?? '', ENT_QUOTES); }

/* Variables */
$company_id = $_SESSION['company_id'];
$company_name = $title = $department = $location = $type = "";
$stipend_raw = $duration = $deadline = $description = "";
$responsibilities = $skills = [];

/* Patterns */
$patternText = "/^[A-Z][A-Za-z0-9\s&.\-']*$/";
$patternDuration = "/^[0-9]+\s?(day|days|week|weeks|month|months|year|years)$/i";

/* ======================
   FETCH COMPANY NAME
====================== */
$stmtC = $conn->prepare("SELECT company_name FROM company_registration WHERE company_id=?");
$stmtC->bind_param("i", $company_id);
$stmtC->execute();
$company_row = $stmtC->get_result()->fetch_assoc();
$company_name = $company_row['company_name'] ?? '';
$stmtC->close();

/* Handle POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* Title */
    $title = trim($_POST['title'] ?? '');
    if ($title === '') {
        $err['title'] = "<div class='error'>Position title is required.</div>";
    } elseif (!preg_match($patternText, $title)) {
        $err['title'] = "<div class='error'>Must start with a capital letter.</div>";
    }

    /* Department */
    $department = trim($_POST['department'] ?? '');
    if ($department === '') {
        $err['department'] = "<div class='error'>Department is required.</div>";
    }

    /* Location */
    $location = trim($_POST['location'] ?? '');
    if ($location === '') {
        $err['location'] = "<div class='error'>Location is required.</div>";
    }

    /* Type */
    $type = $_POST['type'] ?? '';
    if (!in_array($type, ['full-time','part-time','remote'])) {
        $err['type'] = "<div class='error'>Select a valid type.</div>";
    }

    /* Stipend */
    $stipend_raw = trim($_POST['stipend'] ?? '');
    if ($stipend_raw === '' || !preg_match('/^\d+(\.\d{1,2})?$/', $stipend_raw)) {
        $err['stipend'] = "<div class='error'>Enter a valid stipend.</div>";
    }
    $stipend = (float)$stipend_raw;

    /* Duration */
    $duration = trim($_POST['duration'] ?? '');
    if (!preg_match($patternDuration, $duration)) {
        $err['duration'] = "<div class='error'>Example: 3 months, 2 weeks</div>";
    }

    /* Deadline */
    $deadline = $_POST['deadline'] ?? '';
    if ($deadline === '') {
        $err['deadline'] = "<div class='error'>Deadline required.</div>";
    } else {
        $deadlineDate = DateTime::createFromFormat('Y-m-d', $deadline);
        $today = new DateTime('today');

        if (!$deadlineDate) {
            $err['deadline'] = "<div class='error'>Invalid date format.</div>";
        } elseif ($deadlineDate < $today) {
            $err['deadline'] = "<div class='error'>Deadline cannot be in the past.</div>";
        }
    }

    /* Description */
    $description = trim($_POST['description'] ?? '');
    if (strlen($description) < 20) {
        $err['description'] = "<div class='error'>Minimum 20 characters.</div>";
    }

    /* Responsibilities */
    foreach ($_POST['responsibility'] ?? [] as $i => $r) {
        $r = trim($r);
        if ($r !== '') $responsibilities[] = $r;
    }
    if (count($responsibilities) === 0) {
        $err['responsibility'][0] = "<div class='error'>Add at least one responsibility.</div>";
    }

    /* Skills */
    foreach ($_POST['skill'] ?? [] as $i => $s) {
        $s = trim($s);
        if ($s !== '') $skills[] = $s;
    }
    if (count($skills) === 0) {
        $err['skill'][0] = "<div class='error'>Add at least one skill.</div>";
    }

    /* Insert into DB */
    if (empty($err)) {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("
                INSERT INTO postposition
                (company_id, company_name, title, department, location, type, stipend, duration, deadline, description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "isssssdsss",
                $company_id, $company_name, $title, $department, $location,
                $type, $stipend, $duration, $deadline, $description
            );
            $stmt->execute();
            $position_id = $stmt->insert_id;
            $stmt->close();

            $stmtR = $conn->prepare("INSERT INTO position_responsibilities (position_id, responsibility) VALUES (?, ?)");
            foreach ($responsibilities as $r) {
                $stmtR->bind_param("is", $position_id, $r);
                $stmtR->execute();
            }
            $stmtR->close();

            $stmtS = $conn->prepare("INSERT INTO position_skills (position_id, skill) VALUES (?, ?)");
            foreach ($skills as $s) {
                $stmtS->bind_param("is", $position_id, $s);
                $stmtS->execute();
            }
            $stmtS->close();

            $conn->commit();
            // Redirect to dashboard page after posting
            header("Location: /project/html/Company/CDashboard.php?success=1");
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            $err['general'] = "<div class='error'>Database error. Try again.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Post New Position</title>
<style>
body { font-family: Segoe UI, sans-serif; background:#f5f7fb; padding:40px 0; }
.form-container { max-width:820px; margin:auto; background:#fff; padding:35px 40px; border-radius:14px; box-shadow:0 10px 30px rgba(0,0,0,.08); }
label { font-weight:600; margin-bottom:6px; display:block; }
input, select, textarea { width:100%; padding:11px; border-radius:8px; border:1px solid #cfd6e4; }
textarea { min-height:120px; }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
.form-group { margin-bottom:18px; }
.error { color:#dc2626; font-size:13px; margin-top:4px; }
.add-btn { background:#eef2ff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
.form-actions { display:flex; justify-content:flex-end; gap:12px; }
.btn { padding:10px 18px; border-radius:8px; border:none; }
.submit { background:#2563eb; color:#fff; }
.cancel { background:#e5e7eb; }
</style>
</head>

<body>
<div class="form-container">
<h1>Post New Position</h1>
<p class="subtitle">Create a new internship opportunity.</p>

<?php echo $err['general'] ?? ''; ?>

<form method="POST">

<!-- Company Name -->
<div class="form-group">
    <label for="company-name">Company Name *</label>
    <input type="text" name="company_name_display" id="company-name"
           value="<?php echo htmlspecialchars($company_name); ?>" disabled>
    <input type="hidden" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>">
</div>

<!-- Title -->
<div class="form-group">
    <label for="title">Position Title *</label>
    <input type="text" name="title" id="title" value="<?php echo $title; ?>">
    <?php echo $err['title'] ?? ''; ?>
</div>

<!-- Department + Location -->
<div class="form-row">
    <div class="form-group">
        <label>Department *</label>
        <input name="department" value="<?php echo $department; ?>">
        <?php echo $err['department'] ?? ''; ?>
    </div>

    <div class="form-group">
        <label>Location *</label>
        <input name="location" value="<?php echo $location; ?>">
        <?php echo $err['location'] ?? ''; ?>
    </div>
</div>

<!-- Type + Stipend -->
<div class="form-row">
    <div class="form-group">
        <label>Type *</label>
        <select name="type">
            <option value="">Select Type</option>
                        <option value="full-time" <?php echo ($type==='full-time')?'selected':''; ?>>Full-time</option>
            <option value="part-time" <?php echo ($type==='part-time')?'selected':''; ?>>Part-time</option>
            <option value="remote" <?php echo ($type==='remote')?'selected':''; ?>>Remote</option>
        </select>
        <?php echo $err['type'] ?? ''; ?>
    </div>

    <div class="form-group">
        <label>Stipend *</label>
        <input name="stipend" value="<?php echo $stipend_raw; ?>">
        <?php echo $err['stipend'] ?? ''; ?>
    </div>
</div>

<!-- Duration + Deadline -->
<div class="form-row">
    <div class="form-group">
        <label>Duration *</label>
        <input name="duration" value="<?php echo $duration; ?>">
        <?php echo $err['duration'] ?? ''; ?>
    </div>

    <div class="form-group">
        <label>Application Deadline *</label>
        <input type="date" name="deadline" value="<?php echo $deadline; ?>">
        <?php echo $err['deadline'] ?? ''; ?>
    </div>
</div>

<!-- Description -->
<div class="form-group">
    <label>Job Description *</label>
    <textarea name="description"><?php echo $description; ?></textarea>
    <?php echo $err['description'] ?? ''; ?>
</div>

<!-- Responsibilities -->
<div class="form-group">
    <label>Responsibilities *</label>
    <div id="responsibilities">
        <?php
        $oldResp = $_POST['responsibility'] ?? [''];
        foreach ($oldResp as $i => $val):
        ?>
            <input name="responsibility[]" value="<?php echo esc($val); ?>">
            <?php echo $err['responsibility'][$i] ?? ''; ?>
        <?php endforeach; ?>
    </div>
    <button type="button" class="add-btn" onclick="addField('responsibilities','responsibility[]')">+ Add</button>
</div>

<!-- Skills -->
<div class="form-group">
    <label>Required Skills *</label>
    <div id="skills">
        <?php
        $oldSkill = $_POST['skill'] ?? [''];
        foreach ($oldSkill as $i => $val):
        ?>
            <input name="skill[]" value="<?php echo esc($val); ?>">
            <?php echo $err['skill'][$i] ?? ''; ?>
        <?php endforeach; ?>
    </div>
    <button type="button" class="add-btn" onclick="addField('skills','skill[]')">+ Add</button>
</div>

<!-- Buttons -->
<div class="form-actions">
    <button type="button" class="btn cancel"
        onclick="window.location.href='/project/html/Company/CDashboard.php';">
        Cancel
    </button>
    <button type="submit" class="btn submit">Post Position</button>
</div>

</form>
</div>

<script>
function addField(id, name) {
    const box = document.getElementById(id);
    const input = document.createElement("input");
    input.name = name;
    box.appendChild(input);
}
</script>

</body>
</html>