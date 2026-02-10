function approveStudent() {
    alert("Student details approved successfully!");
}

function rejectStudent() {
    const confirmReject = confirm("Are you sure you want to reject this student?");
    if (confirmReject) {
        alert("Student details rejected.");
    }
}
