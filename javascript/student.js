//  Utility: Hide all content sections
function hideAllSections() {
  document.getElementById("internship-content").style.display = "none";
  document.getElementById("applyinternship-content").style.display = "none";
  document.getElementById("mylogbook-content").style.display = "none";
  document.getElementById("mycvbuilder").style.display = "none";
}

//  Load My Internships
document.getElementById("myInternships").addEventListener("click", function(e) {
  e.preventDefault();
  hideAllSections(); 

  let container = document.getElementById("internship-content");

  fetch("myinternship.php")
    .then(response => response.text())
    .then(data => {
      container.innerHTML = data;
      container.style.display = "block";
    })
    .catch(error => {
      container.innerHTML = "<p style='color:red;'>Failed to load internship data.</p>";
      container.style.display = "block";
      console.error("Error fetching internship data:", error);
    });
});

//  Load Apply for Internships
document.getElementById("applyInternships").addEventListener("click", function(e) {
  e.preventDefault();
  hideAllSections(); 

  let container = document.getElementById("applyinternship-content");

  fetch("applyforinternship.php")
    .then(response => response.text())
    .then(data => {
      container.innerHTML = data;
      container.style.display = "block";
    })
    .catch(error => {
      container.innerHTML = "<p style='color:red;'>Failed to load internship data.</p>";
      container.style.display = "block";
      console.error("Error fetching internship data:", error);
    });
});

//  Load My Logbook
document.getElementById("myLogbook").addEventListener("click", function(e) {
  e.preventDefault();
  hideAllSections(); // 

  let container = document.getElementById("mylogbook-content");

  fetch("logbook.php")
    .then(response => response.text())
    .then(data => {
      container.innerHTML = data;
      container.style.display = "block";

      // Rebind Add Entry button after content is loaded
      const addEntryBtn = document.getElementById("addEntry");
      if (addEntryBtn) {
        addEntryBtn.addEventListener("click", function() {
          const popup = document.getElementById("popupOverlay");
          if (popup) {
            popup.style.display = "block";
          } else {
            console.warn("Popup overlay not found.");
          }
        });
      } else {
        console.warn("Add Entry button not found.");
      }
    })
    .catch(error => {
      container.innerHTML = "<p style='color:red;'>Failed to load logbook data.</p>";
      container.style.display = "block";
      console.error("Error fetching logbook data:", error);
    });
});

// Load My CV Builder
document.getElementById("myCV").addEventListener("click", function(e) {
  e.preventDefault();
  hideAllSections(); 

  let container = document.getElementById("mycvbuilder");

  fetch("cvbuilder.php")
    .then(response => response.text())
    .then(data => {
      container.innerHTML = data;
      container.style.display = "block";
    })
    .catch(error => {
      container.innerHTML = "Failed to load CV builder content.";
      container.style.display = "block";
      console.error("Error fetching CV builder:", error);
    });
});

