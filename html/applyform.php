<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Job Application Form</title>
  <link rel="stylesheet" href="/PROJECT/css/proj/applyform.css" />
</head>
<body>
  <div class="form-container">
    <h1>Job Application Form</h1>
    <p>Please Fill Out the Form Below to Submit Your Job Application!</p>

    <form action="submit.php" method="POST" enctype="multipart/form-data">
      <!-- Personal Info -->
      <div class="form-group">
        <label>First Name</label>
        <input type="text" name="firstName"  />
        <label>Last Name</label>
        <input type="text" name="lastName"  />
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"  />
        <label>Phone Number</label>
        <input type="tel" name="phone"  />
      </div>

      <!-- Job Details -->
      <div class="form-group">
        <label>Applied Position</label>
        <input type="text" name="position"  />
        <label>Available Start Date</label>
        <input type="date" name="startDate"  />
      </div>

      <!-- Interview Scheduling -->
      <div class="form-group">
        <label>Preferred Interview Date</label>
        <input type="date" name="interviewDate"  />
        <label>Preferred Interview Time</label>
        <select name="interviewTime" >
          <option value="">Select a time</option>
          <option>9:00 AM</option>
          <option>9:30 AM</option>
          <option>10:00 AM</option>
          <option>10:30 AM</option>
        </select>
      </div>

      <!-- Cover Letter -->
      <div class="form-group">
        <label>Cover Letter (min 200 words)</label>
        <textarea name="coverLetter" rows="6" ></textarea>
      </div>

      <!-- File Uploads -->
      <div class="form-group">
        <label>Upload Resume</label>
        <input type="file" name="resume"  />
        <label>Upload Additional Documents</label>
        <input type="file" name="additionalDocs" />
      </div>

      <!-- Submit -->
      <button type="submit">Apply</button>
    </form>
  </div>
</body>
</html>