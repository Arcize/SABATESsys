async function acceptRepair(reportId) {
  
    try {
      const response = await fetch("index.php?view=faultReport&action=assign_technician", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `report_id=${encodeURIComponent(reportId)}&action=accept_repair`
      });
  
      if (response.ok) {
        const responseData = await response.json();
        console.log('Server response (Accept Repair):', responseData);
        showMessage('Repair accepted successfully', 'success');
        // Here you can update the user interface
      } else {
        console.error('Error accepting repair:', response.status);
        const errorData = await response.json();
        console.error('Error details:', errorData);
        showMessage('Error accepting repair', 'error');
      }
    } catch (error) {
      console.error('Network error accepting repair:', error);
      showMessage('Network error', 'error');
    }
  }
  
  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('accept-repair-button')) {
      const reportId = event.target.dataset.reportId;
      if (reportId) {
        acceptRepair(reportId);
      } else {
        console.error('Report ID not found on the button.');
        showMessage('Error: Report ID not found', 'error');
      }
    }
  });
  
  // Example function to show messages (make sure it's defined or move it as well)
  function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.textContent = message;
    messageDiv.classList.add('message', type);
    document.body.appendChild(messageDiv);
  
    setTimeout(() => {
      messageDiv.remove();
    }, 3000);
  }