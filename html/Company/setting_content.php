<?php
// Fetch current settings for logged-in company
$company_id = $_SESSION['company_id'];
$stmt = $conn->prepare("SELECT theme_bg, theme_font FROM company_settings WHERE company_id=?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$theme = $stmt->get_result()->fetch_assoc();
$stmt->close();

$theme_bg   = $theme['theme_bg'] ?? '#f4f6f8';
$theme_font = $theme['theme_font'] ?? 'Arial';
?>

<h2>Dashboard Settings</h2>

<!-- THEME CUSTOMIZATION -->
<form action="save_setting.php" method="POST" class="settings-box">
  <h3>🎨 Appearance</h3>

  <label>Background Color</label>
  <input type="color" name="theme_bg" value="<?= htmlspecialchars($theme_bg) ?>">

  <label>Font Style</label>
  <select name="theme_font">
    <option value="Arial" <?= $theme_font=='Arial'?'selected':'' ?>>Arial</option>
    <option value="Verdana" <?= $theme_font=='Verdana'?'selected':'' ?>>Verdana</option>
    <option value="Tahoma" <?= $theme_font=='Tahoma'?'selected':'' ?>>Tahoma</option>
    <option value="Georgia" <?= $theme_font=='Georgia'?'selected':'' ?>>Georgia</option>
    <option value="Courier New" <?= $theme_font=='Courier New'?'selected':'' ?>>Courier New</option>
  </select>

  <button type="submit" name="save_theme">Save Changes</button>
</form>

<hr>

<!-- DELETE ACCOUNT -->
<div class="danger-box">
  <h3>⚠️ Danger Zone</h3>
  <p>Deleting your account is permanent and cannot be undone.</p>

  <form action="delete_account.php" method="POST"
        onsubmit="return confirm('Are you sure? This will permanently delete your account.')">
    <button class="danger-btn" name="delete_account">Delete My Account</button>
  </form>
</div>

<style>
.settings-box {
  max-width: 450px;
  background: #f4f6f8;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 20px;
}
.settings-box label {
  display: block;
  font-weight: bold;
  margin-top: 12px;
}
.settings-box input,
.settings-box select {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
}
.settings-box button {
  margin-top: 15px;
  padding: 10px;
  background: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}
.settings-box button:hover {
  background: #0056b3;
}

.danger-box {
  max-width: 450px;
  background: #ffecec;
  padding: 20px;
  border-radius: 8px;
}
.danger-btn {
  background: #d00000;
  color: white;
  padding: 10px;
  border: none;
  cursor: pointer;
}
</style>
