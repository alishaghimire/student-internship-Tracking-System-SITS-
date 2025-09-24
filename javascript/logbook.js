
  const popup = document.getElementById("popupOverlay");

  document.querySelector(".add-entry").addEventListener("click", () => {
    popup.style.display = "block";
  });

  function closeForm() {
    popup.style.display = "none";
  }

  document.getElementById("logbookForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // You can add logic here to save the entry or push it to the table
    alert("Entry saved!");
    this.reset();
    closeForm();
  });
