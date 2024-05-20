function showResponseModal(message, callback) {
    // Set the text of the modal
    document.getElementById('responseText').textContent = message;

    // Show the modal
    document.getElementById('responseModal').style.display = 'block';



    // Close the modal after 5 seconds
    setTimeout(function () {
        document.getElementById('responseModal').style.display = 'none';
        // Execute the callback if it's a function
        if (typeof callback === 'function') {
            callback(); // Call the callback function, used to refresh the page
        }
    }, 2000);
}

// When the user clicks anywhere outside of the modal, close it
$(window).click(function (event) {
    if (event.target == document.getElementById('responseModal')) {
        $('#responseModal').hide();
    }
});