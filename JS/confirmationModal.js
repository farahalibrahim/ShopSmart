function closeConfirmationModal() {
    // Hide the modal
    document.getElementById('confirmationModal').style.display = 'none';
}

function showConfirmationModal(message, callback) {
    // Set the text of the modal
    document.getElementById('confirmText').textContent = message;

    // Show the modal
    document.getElementById('confirmationModal').style.display = 'block';

    // Set the click handler for the "Yes" button
    document.getElementById('confirmYes').onclick = function () {
        // Hide the modal
        closeConfirmationModal();

        // Call the callback function
        callback();
    };

    // Set the click handler for the "No" button
    document.getElementById('confirmNo').onclick = function () {
        // Hide the modal
        closeConfirmationModal();
    };
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    var modal = document.getElementById('confirmationModal');
    if (event.target == modal) {
        closeConfirmationModal();
    }
};