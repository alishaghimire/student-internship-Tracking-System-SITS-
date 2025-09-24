<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Menu</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .profile-wrapper {
      position: relative;
      display: inline-block;
    }

    .profile-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
      position: relative;
    }

    .profile-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }

    .status-dot {
      position: absolute;
      top: 0;
      right: 0;
      width: 10px;
      height: 10px;
      background: green;
      border: 2px solid white;
      border-radius: 50%;
    }

    .profile-menu {
      position: absolute;
      top: 50px;
      right: 0;
      width: 220px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
      z-index: 100;
    }

    .profile-menu.show {
      opacity: 1;
      visibility: visible;
    }

    .profile-header {
      text-align: center;
      padding: 10px;
      border-bottom: 1px solid #eee;
    }

    .profile-header img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }

    .profile-header h4 {
      margin: 8px 0 4px;
    }

    .profile-header p {
      font-size: 0.9em;
      color: #666;
    }

    .profile-menu ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .profile-menu ul li {
      padding: 12px 16px;
      border-bottom: 1px solid #eee;
    }

    .profile-menu ul li a {
      text-decoration: none;
      color: #333;
      display: block;
    }

    .profile-menu ul li:hover {
      background: #f5f5f5;
    }
  </style>
</head>
<body>

<div class="profile-wrapper">
  <div class="profile-icon" onclick="toggleProfileMenu()">
    <img src="../pictures/profile.jpg" alt="Profile" />
    <span class="status-dot"></span>
  </div>
  <div class="profile-menu" id="profileMenu">
    <div class="profile-header">
      <img src="../pictures/profile.jpg" alt="Ram" />
      <h4>Ram</h4>
      <p>ram@student.edu.np</p>
    </div>
    <ul>
      <li><a href="#" onclick="loadContent('edit')">Edit Profile</a></li>
      <li><a href="#" onclick="loadContent('settings')">Settings & Privacy</a></li>
      <li><a href="#" onclick="loadContent('help')">Help & Support</a></li>
      <li><a href="#" onclick="loadContent('display')">Display & Accessibility</a></li>
      <li><a href="login.php">Logout</a></li>
    </ul>
  </div>
</div>

<!-- Optional content area -->
<div id="contentArea" style="margin-top: 20px;"></div>

<script>
  function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');
    menu.classList.toggle('show');
  }

  window.addEventListener('click', function(e) {
    const profile = document.querySelector('.profile-wrapper');
    const menu = document.getElementById('profileMenu');
    if (!profile.contains(e.target)) {
      menu.classList.remove('show');
    }
  });

  function loadContent(section) {
    const content = {
      edit: "<h3>Edit Profile</h3><p>Here you can update your name, photo, and email.</p>",
      settings: "<h3>Settings & Privacy</h3><p>Manage your account settings and privacy preferences.</p>",
      help: "<h3>Help & Support</h3><p>Find answers to common questions and contact support.</p>",
      display: "<h3>Display & Accessibility</h3><p>Customize your display and accessibility options.</p>"
    };
    document.getElementById('contentArea').innerHTML = content[section] || "";
  }
</script>

</body>
</html>