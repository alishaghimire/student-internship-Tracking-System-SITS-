// document.getElementById("postPositionBtn").addEventListener("click", function () {

//   // show modal
//   document.getElementById("postPositionModal").style.display = "flex";

//   // Load the form from another page into the modal
//   fetch("/SITS/Html/company/postposition.php")
//     .then(response => response.text())
//     .then(html => {
//       document.getElementById("formContainer").innerHTML = html;
//     })
//     .catch(err => {
//       document.getElementById("formContainer").innerHTML = "<p>Error loading form.</p>";
//     });
// });
// //for add
// fetch("/SITS/Html/company/postposition.php")
//   .then(res => res.text())
//   .then(html => {
//     document.getElementById("formContainer").innerHTML = html;

//     // Execute scripts inside the fetched HTML
//     document.querySelectorAll("#formContainer script").forEach(oldScript => {
//       const newScript = document.createElement("script");
//       newScript.text = oldScript.textContent;
//       document.body.appendChild(newScript);
//     });
//   });
  //application
document.addEventListener("click", function(e) {
    if (e.target && e.target.id === "reviewApplicationsBtn") {
        document.getElementById("applicationNav").click();
    }
});
//for interview 
document.addEventListener("click", function(e) {
    if (e.target && e.target.id === "scheduleInterviewBtn") {
        document.getElementById("interviewNav").click();
    }
});

//for report
document.addEventListener("click", function(e) {
    if (e.target && e.target.id === "viewreportBtn") {
        document.getElementById("reportNav").click();
    }
});
//for message
document.addEventListener("click", function(e) {
    if (e.target && e.target.id === "messagestudentBtn") {
        document.getElementById("messageNav").click();
    }
});
//for setting
document.addEventListener("click", function(e) {
    if (e.target && e.target.id === "messagesettingBtn") {
        document.getElementById("settingsNav").click();
    }
});


