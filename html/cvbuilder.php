<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CV Builder</title>
  <link rel="stylesheet" href="../css/proj/cvbuilder.css" />
</head>
<body>
  <div class="cv-builder">
  <div class="cv-header">
    <h1>CV Builder</h1>
    <div class="actions">
      <button id="saveBtn">Save CV</button>
      <button id="downloadBtn">Download PDF</button>
    </div>
  </div>

  <main>
    <!-- Personal Info -->
    <section class="cv-section" id="personal-info">
      <h2>Personal Information</h2>
      <input type="text" placeholder="Full Name" />
      <input type="email" placeholder="Email" />
      <input type="text" placeholder="Phone" />
      <input type="text" placeholder="Address" />
      <input type="text" placeholder="LinkedIn" />
      <input type="text" placeholder="GitHub" />
    </section>

    <!-- Career Objective -->
    <section class="cv-section" id="career-objective">
      <h2>Career Objective</h2>
      <textarea placeholder="Write a brief objective or summary about your career goals..."></textarea>
    </section>

    <!-- Skills -->
    <section class="cv-section" id="skills">
      <h2>Skills</h2>
      <div class="skill-tags">
        <!-- dynamically added skills -->
      </div>
      <input type="text" id="skillInput" placeholder="Add a skill" />
      <button id="addSkillBtn">+</button>
    </section>

    <!-- Education -->
    <section class="cv-section" id="education">
      <h2>Education</h2>
      <div class="edu-entry">
        <input type="text" placeholder="Institution" />
        <input type="text" placeholder="Degree" />
        <input type="text" placeholder="Field of Study" />
        <input type="text" placeholder="GPA (Optional)" />
        <input type="text" placeholder="Start Year" />
        <input type="text" placeholder="End Year" />
      </div>
      <button class="add-btn">+ Add Education</button>
    </section>

    <!-- Work Experience -->
    <section class="cv-section" id="experience">
      <h2>Work Experience</h2>
      <div class="exp-entry">
        <input type="text" placeholder="Company" />
        <input type="text" placeholder="Position" />
        <input type="text" placeholder="Start Date" />
        <input type="text" placeholder="End Date" />
        <textarea placeholder="Describe your responsibilities and achievements..."></textarea>
      </div>
      <button class="add-btn">+ Add Experience</button>
    </section>

    <!-- Projects -->
    <section class="cv-section" id="projects">
      <h2>Projects</h2>
      <p>No projects added yet. Add your academic or personal projects.</p>
      <button class="add-btn">+ Add Project</button>
    </section>

    <!-- Achievements -->
    <section class="cv-section" id="achievements">
      <h2>Achievements & Awards</h2>
      <input type="text" placeholder="Add an achievement or award" />
      <button class="add-btn">+</button>
    </section>
  </main>

  <script src="cvbuilder.js"></script>
</body>
</html>