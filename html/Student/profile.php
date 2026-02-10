<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>

  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: #f1f1f1;
      display: flex;
      height: 100vh;
    }

    /* LEFT SIDEBAR */
    .sidebar {
      width: 260px;
      background: #004a96;
      color: white;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }

    .sidebar h2 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .menu a {
      display: block;
      padding: 12px 15px;
      margin: 6px 0;
      color: white;
      text-decoration: none;
      background: rgba(255,255,255,0.1);
      border-radius: 6px;
      transition: 0.3s;
      cursor: pointer;
    }

    .menu a:hover,
    .menu a.active {
      background: white;
      color: #004a96;
      font-weight: bold;
    }

    /* RIGHT CONTENT */
    .content {
      flex: 1;
      padding: 35px;
      overflow-y: auto;
    }

    .card {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      width: 95%;
      margin: auto;
    }

    h3 {
      margin-top: 0;
      color: #004a96;
      font-size: 22px;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 12px;
      font-weight: 500;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #bbb;
      border-radius: 6px;
      font-size: 1rem;
    }

    .btn {
      margin-top: 20px;
      background: #004a96;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }

    .btn:hover {
      background: #003b78;
    }

    .section {
      display: none;
    }

    .active-section {
      display: block;
    }

  </style>
</head>

<body>

  <!-- LEFT SIDEBAR -->
  <div class="sidebar">
    <h2>My Profile</h2>

    <div class="menu">
      <a class="active" onclick="showSection('personal')">Personal Detail</a>
      <a onclick="showSection('contact')">Contact Detail</a>
      <a onclick="showSection('education')">Education</a>
      <a onclick="showSection('preview')">Preview</a>
    </div>
  </div>

  <!-- RIGHT CONTENT -->
  <div class="content">
    <div class="card">

      <!-- Personal Section -->
      <section id="personal" class="section active-section">
        <h3>Personal Detail</h3>

        <label>Full Name:</label>
        <input type="text" id="fullName">

        <label>Student ID / Roll Number:</label>
        <input type="text" id="studentId">

        <label>Date of Birth:</label>
        <input type="date" id="dob">

        <label>Address:</label>
        <input type="text" id="address">

        <button class="btn" onclick="showSection('contact')">Next</button>
      </section>

      <!-- Contact Section -->
      <section id="contact" class="section">
        <h3>Contact Detail</h3>

        <label>Email:</label>
        <input type="email" id="email">

        <label>Phone:</label>
        <input type="tel" id="phone">

        <button class="btn" onclick="showSection('education')">Next</button>
      </section>

      <!-- Education Section -->
      <section id="education" class="section">
        <h3>Education</h3>

        <label>University:</label>
        <input type="text" id="university">

        <label>Department:</label>
        <input type="text" id="department">

        <label>Year of Study:</label>
        <select id="year">
          <option>1st Year</option>
          <option>2nd Year</option>
          <option>3rd Year</option>
          <option>4th Year</option>
        </select>

        <label>GPA:</label>
        <input type="text" id="gpa">

        <label>Courses:</label>
        <textarea id="courses"></textarea>

        <button class="btn" onclick="generatePreview(); showSection('preview');">Next</button>
      </section>

      <!-- Preview Section -->
      <section id="preview" class="section">
        <h3>Preview</h3>

        <div id="cvPreview" style="line-height:1.6;"></div>

        <button class="btn">Submit Profile</button>
      </section>

    </div>
  </div>

<script>
function showSection(sectionId) {
  document.querySelectorAll(".section").forEach(s => s.classList.remove("active-section"));
  document.getElementById(sectionId).classList.add("active-section");

  document.querySelectorAll(".menu a").forEach(a => a.classList.remove("active"));
  event.target.classList.add("active");
}

function generatePreview() {
  document.getElementById("cvPreview").innerHTML = `
    <h3 style="margin-top:0;">${fullName.value}</h3>
    <p><b>Student ID:</b> ${studentId.value}</p>
    <p><b>Date of Birth:</b> ${dob.value}</p>
    <p><b>Address:</b> ${address.value}</p>

    <p><b>Email:</b> ${email.value}</p>
    <p><b>Phone:</b> ${phone.value}</p>

    <h3>Education</h3>
    <p><b>University:</b> ${university.value}</p>
    <p><b>Department:</b> ${department.value}</p>
    <p><b>Year:</b> ${year.value}</p>
    <p><b>GPA:</b> ${gpa.value}</p>
    <p><b>Courses:</b><br> ${courses.value}</p>
  `;
}
</script>

</body>
</html>
