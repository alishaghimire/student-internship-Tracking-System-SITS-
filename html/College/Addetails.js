document.getElementById("logForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const date = document.getElementById("dateInput").value;
    const hours = document.getElementById("hoursInput").value;
    const activity = document.getElementById("activityInput").value;
    const desc = document.getElementById("descInput").value;
    const status = document.getElementById("statusInput").value;

    addEntry(date, hours, activity, desc, status);

    this.reset();
});

function addEntry(date, hours, activity, desc, status) {
    const entryList = document.getElementById("entryList");

    const card = document.createElement("div");
    card.classList.add("entry-card");

    card.innerHTML = `
        <div class="entry-header">
            <h4>${activity}</h4>
            <span class="status-badge ${status.toLowerCase().replace(" ", "")}">
                ${status}
            </span>
        </div>
        <p>${desc}</p>
        <small>📅 ${date} | ⏱ ${hours} hours</small>
        <br><br>
        <button class="icon-btn delete-btn">🗑</button>
    `;

    entryList.appendChild(card);

    card.querySelector(".delete-btn").addEventListener("click", () => {
        card.remove();
    });
}
