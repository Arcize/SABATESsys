const tableId = "faultReportTable"; // Replace with your actual table ID
async function acceptRepair(reportId) {

  try {
    const body = new URLSearchParams({
      report_id: reportId,
      state: 2,
    })
    const response = await fetch(
      "index.php?view=faultReport&action=assign_technician",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: body,

      }
    );

    if (response.ok) {
      acceptRepairSuccess();
      const responseData = await response.json();
      console.log("Server response (Accept Repair):", responseData);
      $(`#${tableId}`).DataTable().ajax.reload(null, false); // false mantiene la página actual
      // Here you can update the user interface
    } else {
      console.error("Error accepting repair:", response.status);
      const errorData = await response.json();
      console.error("Error details:", errorData);
      showMessage("Error accepting repair", "error");
    }
  } catch (error) {
    console.error("Network error accepting repair:", error);
    showMessage("Network error", "error");
  }
}

async function rejectRepair(reportId) {
  try {
    const body = new URLSearchParams({
      report_id: reportId,
      state: 1,
    })
    const response = await fetch(
      "index.php?view=faultReport&action=unassign_technician",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: body,
      }
    );

    if (!response.ok) {
      const errorData = await response.json();
      console.error("Error rejecting repair:", response.status, errorData);
      showMessage("Error rejecting repair", "error");
      return;
    }
    rejectRepairSuccess();

    const responseData = await response.json();
    console.log("Server response (Reject Repair):", responseData);
    $(`#${tableId}`).DataTable().ajax.reload(null, false); // Mantiene la página actual
    // Aquí puedes actualizar la interfaz de usuario
  } catch (error) {
    console.error("Network error rejecting repair:", error);
    showMessage("Network error", "error");
  }
}

// Example function to show messages (make sure it's defined or move it as well)
function showMessage(message, type) {
  const messageDiv = document.createElement("div");
  messageDiv.textContent = message;
  messageDiv.classList.add("message", type);
  document.body.appendChild(messageDiv);

  setTimeout(() => {
    messageDiv.remove();
  }, 3000);
}
