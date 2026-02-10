<?php
// postposition.php
// Make sure dbconnectcompany.php sets $conn = new mysqli(...)
session_start();
require_once "dbconnectcompany.php";

$err = [];
// Initialize variables for form population
$title = $company_name = $department = $location = $type = '';
$stipend_raw = $stipend = $duration = $deadline = $description = '';
$responsibilities = $skills = [];

// Helper to escape output
function esc($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES);
}

// Pattern rules (as you requested)
$patternA = "/^[A-Z][A-Za-z0-9\s&.\-']*$/";                     // Starts with capital, allowed chars
$patternDuration = "/^[0-9]+\s?(day|days|week|weeks|month|months|year|years)$/"; // e.g., 3 months
$patternResponsibility = "/^[A-Z].{4,}$/u"; // start with capital, at least 5 chars (multibyte safe)
$patternSkill = "/^[A-Z].{2,}$/u";          // start with capital, at least 3 chars

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---------------------
    // 1. TITLE (A)
    // ---------------------
    $title = trim($_POST['title'] ?? '');
    if ($title === '') {
        $err['title'] = 'Title is required.';
    } elseif (!preg_match($patternA, $title)) {
        $err['title'] = 'Title must start with a capital letter and contain only letters, numbers, spaces or & . - \'.';
    } elseif (mb_strlen($title) > 255) {
        $err['title'] = 'Title too long (max 255 chars).';
    }

    // ---------------------
    // 2. COMPANY NAME (A)
    // ---------------------
    $company_name = trim($_POST['company_name'] ?? '');
    if ($company_name === '') {
        $err['company_name'] = 'Company name is required.';
    } elseif (!preg_match($patternA, $company_name)) {
        $err['company_name'] = 'Company name must start with a capital letter and contain only letters, numbers, spaces or & . - \'.';
    } elseif (mb_strlen($company_name) > 100) {
        $err['company_name'] = 'Company name too long (max 100 chars).';
    }

    // ---------------------
    // 3. DEPARTMENT (A)
    // ---------------------
    $department = trim($_POST['department'] ?? '');
    if ($department === '') {
        $err['department'] = 'Department is required.';
    } elseif (!preg_match($patternA, $department)) {
        $err['department'] = 'Department must start with a capital letter and contain only letters, numbers, spaces or & . - \'.';
    } elseif (mb_strlen($department) > 255) {
        $err['department'] = 'Department too long (max 255 chars).';
    }

    // ---------------------
    // 4. LOCATION (A)
    // ---------------------
    $location = trim($_POST['location'] ?? '');
    if ($location === '') {
        $err['location'] = 'Location is required.';
    } elseif (!preg_match($patternA, $location)) {
        $err['location'] = 'Location must start with a capital letter and contain only letters, numbers, spaces or & . - \'.';
    } elseif (mb_strlen($location) > 255) {
        $err['location'] = 'Location too long (max 255 chars).';
    }

    // ---------------------
    // 5. TYPE
    // ---------------------
    $type = trim($_POST['type'] ?? '');
    $allowed_types = ['full-time','part-time','remote'];
    if ($type === '') {
        $err['type'] = 'Please select your type.';
    } elseif (!in_array($type, $allowed_types, true)) {
        $err['type'] = 'Please select a valid type.';
    }

    // ---------------------
    // 6. STIPEND (numeric)
    // ---------------------
    $stipend_raw = trim($_POST['stipend'] ?? '');
    if ($stipend_raw === '') {
        $err['stipend'] = 'Enter the stipend amount.';
    } elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $stipend_raw)) {
        $err['stipend'] = 'Stipend must be a valid number (e.g. 1000 or 1000.50).';
    } else {
        $stipend = (float)$stipend_raw;
    }

    // ---------------------
    // 7. DURATION (A)
    // ---------------------
    $duration = trim($_POST['duration'] ?? '');
    if ($duration === '') {
        $err['duration'] = 'Enter the duration.';
    } elseif (!preg_match($patternDuration, $duration)) {
        $err['duration'] = 'Duration must be like "3 months", "2 weeks", "10 days" (numeric + unit).';
    } elseif (mb_strlen($duration) > 255) {
        $err['duration'] = 'Duration too long (max 255 chars).';
    }

    // ---------------------
    // 8. DEADLINE (date)
    // ---------------------
    $deadline = trim($_POST['deadline'] ?? '');
    if ($deadline === '') {
        $err['deadline'] = 'Enter the application deadline.';
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $deadline);
        $errors = DateTime::getLastErrors();
        if (!$d || ($errors['warning_count'] + $errors['error_count'] > 0)) {
            $err['deadline'] = 'Deadline must be a valid date (YYYY-MM-DD).';
        } else {
            $today = (new DateTime())->setTime(0,0,0);
            $d->setTime(0,0,0);
            if ($d < $today) {
                $err['deadline'] = 'Deadline cannot be in the past.';
            }
        }
    }

    // ---------------------
    // 9. DESCRIPTION (B)
    // ---------------------
    $description = trim($_POST['description'] ?? '');
    if ($description === '') {
        $err['description'] = 'Enter the job description.';
    } elseif (mb_strlen($description) < 20) {
        $err['description'] = 'Job description must be at least 20 characters.';
    } elseif (preg_match('/[<>]/', $description)) {
        $err['description'] = 'Description cannot contain < or > characters.';
    }

    // ---------------------
    // 10. RESPONSIBILITIES (A + E)
    // ---------------------
    $responsibilities = [];
    if (isset($_POST['responsibility']) && is_array($_POST['responsibility'])) {
        foreach ($_POST['responsibility'] as $i => $rRaw) {
            $r = trim((string)$rRaw);
            if ($r === '') continue; // ignore blanks
            if (!preg_match($patternResponsibility, $r)) {
                $err['responsibility'][$i] = 'Responsibility must start with a capital letter and be at least 5 characters.';
            } elseif (preg_match('/[<>]/', $r)) {
                $err['responsibility'][$i] = 'Responsibility cannot contain < or >.';
            } elseif (mb_strlen($r) > 1000) {
                $err['responsibility'][$i] = 'Responsibility is too long.';
            } else {
                $responsibilities[] = $r;
            }
        }
    }
    if (count($responsibilities) === 0) {
        // if there are individual responsibility errors already, keep them; otherwise set this
        if (empty($err['responsibility'])) {
            $err['responsibility'][0] = 'Enter at least one responsibility.';
        }
    }

    // ---------------------
    // 11. SKILLS (A + E)
    // ---------------------
    $skills = [];
    if (isset($_POST['skill']) && is_array($_POST['skill'])) {
        foreach ($_POST['skill'] as $i => $sRaw) {
            $s = trim((string)$sRaw);
            if ($s === '') continue; // ignore blanks
            if (!preg_match($patternSkill, $s)) {
                $err['skill'][$i] = 'Skill must start with a capital letter and be at least 3 characters.';
            } elseif (preg_match('/[<>]/', $s)) {
                $err['skill'][$i] = 'Skill cannot contain < or >.';
            } elseif (mb_strlen($s) > 255) {
                $err['skill'][$i] = 'Skill is too long.';
            } else {
                $skills[] = $s;
            }
        }
    }
   if (count($skills) === 0) {
    if (empty($err['skill'])) {
        $err['skill'][0] = 'Enter at least one skill.';
    }
}

if (empty($err)) {

    $conn->begin_transaction();

    try {

        $company_id = $_SESSION['company_id'];

        // INSERT MAIN POSITION
        $stmt = $conn->prepare("
            INSERT INTO postposition 
            (company_id, title, company_name, department, location, type, stipend, duration, deadline, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param(
            "isssssdsss",
            $company_id,
            $title,
            $company_name,
            $department,
            $location,
            $type,
            $stipend,
            $duration,
            $deadline,
            $description
        );

        if (!$stmt->execute()) {
            throw new Exception("Insert Error: " . $stmt->error);
        }

        $position_id = (int)$stmt->insert_id;
        $stmt->close();

        // INSERT RESPONSIBILITIES
        $stmtResp = $conn->prepare("
            INSERT INTO position_responsibilities (position_id, responsibility)
            VALUES (?, ?)
        ");

        if (!$stmtResp) {
            throw new Exception('Prepare failed (responsibility): ' . $conn->error);
        }

        foreach ($responsibilities as $r) {
            $r = trim($r);
            $stmtResp->bind_param("is", $position_id, $r);
            if (!$stmtResp->execute()) {
                throw new Exception('Responsibility Insert Error: ' . $stmtResp->error);
            }
        }

        $stmtResp->close();

        // INSERT SKILLS
        $stmtSkill = $conn->prepare("
            INSERT INTO position_skills (position_id, skill)
            VALUES (?, ?)
        ");

        if (!$stmtSkill) {
            throw new Exception('Prepare failed (skill): ' . $conn->error);
        }

        foreach ($skills as $s) {
            $s = trim($s);
            $stmtSkill->bind_param("is", $position_id, $s);
            if (!$stmtSkill->execute()) {
                throw new Exception('Skill Insert Error: ' . $stmtSkill->error);
            }
        }

        $stmtSkill->close();

        // COMMIT
        $conn->commit();

        header("Location: postposition.php?success=1");
        exit();

    } catch (Exception $ex) {

        $conn->rollback();
        $err['general'] = 'An error occurred while saving the position. Please try again.';
        // $err['debug'] = $ex->getMessage(); // enable for debugging
    }
}
// display helpers (preserve old input)
function old($name, $default = '') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $v = $_POST[$name] ?? $default;
        if (is_array($v)) return $v; // leave arrays to be handled separately
        return htmlspecialchars($v, ENT_QUOTES);
    }
    return htmlspecialchars($default, ENT_QUOTES);
}
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Post New Position</title>
  <link rel="stylesheet" href="/SITS/Css/company/postposition.css" />
  <style>
    .error { color: #c00; font-size: .95rem; margin-top: .25rem; display:block; }
    .form-group { margin-bottom: 1rem; }
    .dynamic-list input { display:block; margin-bottom:.5rem; width:100%; }
    .inline-half { width:48%; display:inline-block; vertical-align:top; }
    .inline-half.right { float:right; }
  </style>
</head>
<body>
  <?php if (isset($_GET['success'])): ?>
    <script>alert('Position posted successfully!');</script>
  <?php endif; ?>

  <main class="form-container">
    <h1>Post New Position</h1>
    <p class="subtitle">Create a new internship opportunity.</p>

    <?php if (!empty($err['general'])): ?>
      <div class="error"><?php echo esc($err['general']); ?></div>
    <?php endif; ?>

    <form class="position-form" action="" method="POST" novalidate>
      <div class="form-group">
        <label for="title">Position Title *</label>
        <input type="text" id="title" name="title" value="<?php echo esc($title ?: old('title')); ?>"  />
        <?php if (!empty($err['title'])): ?><span class="error"><?php echo esc($err['title']); ?></span><?php endif; ?>
      </div>

      <div class="form-group">
        <label for="company_name">Company Name *</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo esc($company_name ?: old('company_name')); ?>"  />
        <?php if (!empty($err['company_name'])): ?><span class="error"><?php echo esc($err['company_name']); ?></span><?php endif; ?>
      </div>

      <div class="form-row">
        <div class="form-group inline-half">
          <label for="department">Department *</label>
          <input type="text" name="department" id="department" placeholder="Enter your department." value="<?php echo esc($department ?: old('department')); ?>" >
          <?php if (!empty($err['department'])): ?><span class="error"><?php echo esc($err['department']); ?></span><?php endif; ?>
        </div>
        <div class="form-group inline-half right">
         <label for="location">Location *</label>
         <input type="text" name="location" id="location" placeholder="Enter your location." value="<?php echo esc($location ?: old('location')); ?>" >
         <?php if (!empty($err['location'])): ?><span class="error"><?php echo esc($err['location']); ?></span><?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group inline-half">
          <label for="type">Type *</label>
          <select name="type" id="type">
            <option value="">Select Type</option>
            <option value="full-time" <?php echo ($type === 'full-time' || (isset($_POST['type']) && $_POST['type'] === 'full-time')) ? 'selected' : ''; ?>>Full-time</option>
            <option value="part-time" <?php echo ($type === 'part-time' || (isset($_POST['type']) && $_POST['type'] === 'part-time')) ? 'selected' : ''; ?>>Part-time</option>
            <option value="remote" <?php echo ($type === 'remote' || (isset($_POST['type']) && $_POST['type'] === 'remote')) ? 'selected' : ''; ?>>Remote</option>
          </select> 
          <?php if (!empty($err['type'])): ?><span class="error"><?php echo esc($err['type']); ?></span><?php endif; ?>
        </div>

        <div class="form-group inline-half right">
          <label for="stipend">Stipend *</label>
          <input type="text" name="stipend" id="stipend" placeholder="Enter stipend amount." value="<?php echo esc($stipend_raw ?: old('stipend')); ?>" >
          <?php if (!empty($err['stipend'])): ?><span class="error"><?php echo esc($err['stipend']); ?></span><?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group inline-half">
          <label for="duration">Duration *</label>
          <input type="text" name="duration" id="duration" placeholder="Enter duration (e.g., 6 months)" value="<?php echo esc($duration ?: old('duration')); ?>" >
          <?php if (!empty($err['duration'])): ?><span class="error"><?php echo esc($err['duration']); ?></span><?php endif; ?>
        </div>

        <div class="form-group inline-half right">
          <label for="deadline">Application Deadline *</label>
          <input type="date" name="deadline" id="deadline" value="<?php echo esc($deadline ?: old('deadline')); ?>" >
          <?php if (!empty($err['deadline'])): ?><span class="error"><?php echo esc($err['deadline']); ?></span><?php endif; ?>
        </div>
      </div>

      <div class="form-group">
        <label for="description">Job Description *</label>
        <textarea name="description" id="description" rows="5" placeholder="Describe the internship position and what the intern will be doing..."><?php echo esc($description ?: old('description')); ?></textarea>
        <?php if (!empty($err['description'])): ?><span class="error"><?php echo esc($err['description']); ?></span><?php endif; ?>
      </div>

      <div class="dynamic-section">
        <label>Responsibilities</label>
        <div class="dynamic-list" id="responsibilities">
          <?php
            // Show previously submitted responsibilities or one empty field
            $oldResp = $_POST['responsibility'] ?? [];
            if (!empty($oldResp) && is_array($oldResp)) {
                foreach ($oldResp as $i => $rVal) {
                    $val = esc($rVal);
                    echo "<input type=\"text\" name=\"responsibility[]\" placeholder=\"Responsibility " . ($i+1) . "\" value=\"$val\" />";
                    if (!empty($err['responsibility'][$i])) {
                        echo "<span class=\"error\">" . esc($err['responsibility'][$i]) . "</span>";
                    }
                }
            } else {
                // show one blank input
                echo "<input type=\"text\" name=\"responsibility[]\" placeholder=\"Responsibility 1\" value=\"\" />";
                if (!empty($err['responsibility'][0])) {
                    echo "<span class=\"error\">" . esc($err['responsibility'][0]) . "</span>";
                }
            }
          ?>
        </div>
        <button type="button" onclick="addField('responsibilities')">+ Add</button>
      </div>

      <div class="dynamic-section">
        <label>Required Skills *</label>
        <div class="dynamic-list" id="skills">
          <?php
            $oldSkill = $_POST['skill'] ?? [];
            if (!empty($oldSkill) && is_array($oldSkill)) {
                foreach ($oldSkill as $i => $sVal) {
                    $val = esc($sVal);
                    echo "<input type=\"text\" name=\"skill[]\" placeholder=\"Skill " . ($i+1) . "\" value=\"$val\" />";
                    if (!empty($err['skill'][$i])) {
                        echo "<span class=\"error\">" . esc($err['skill'][$i]) . "</span>";
                    }
                }
            } else {
                echo "<input type=\"text\" name=\"skill[]\" placeholder=\"Skill 1\" value=\"\" />";
                if (!empty($err['skill'][0])) {
                    echo "<span class=\"error\">" . esc($err['skill'][0]) . "</span>";
                }
            }
          ?>
        </div>
        <button type="button" onclick="addField('skills')">+ Add</button>
      </div>

      <div class="form-actions">
        <button type="reset" class="btn cancel">Cancel</button>
        <button type="submit" class="btn submit">Post Position</button>
      </div>
    </form>
  </main>

  <script>
    function addField(sectionId) {
      const container = document.getElementById(sectionId);
      const input = document.createElement("input");
      input.type = "text";
      input.name = sectionId.slice(0, -1) + "[]";
      const labelBase = sectionId.slice(0, -1);
      input.placeholder = `${labelBase.charAt(0).toUpperCase() + labelBase.slice(1)} ${container.children.length + 1}`;
      container.appendChild(input);
    }
  </script>
</body>
</html>
