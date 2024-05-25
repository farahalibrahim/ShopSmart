<div id="modal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%;">
        <span id="modal-close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold;">&times;</span>
        <p id="modal-text"></p>
        <p id="modal-freeze-expiry"></p>
        <p>If you believe this is a mistake, reach us out at <a href="mailto:shopsmart.help@gmail.com">shopsmart.help@gmail.com</a></p>
    </div>
</div>

<script>
    function showFreezeModal(userId, text, callback) {
        document.getElementById('modal-text').textContent = text;

        // Retrieve the freeze expiry from the database
        $.ajax({
            url: '../get_freeze_expiry.php',
            method: 'POST',
            data: {
                user_id: userId
            },
            success: function(data) {
                // Set the freeze expiry of the modal
                document.getElementById('modal-freeze-expiry').textContent = 'You can regain access to your account and features after ' + data;

            }
        });

        // Show the modal
        document.getElementById('modal').style.display = 'block';
        if (typeof callback === 'function') {
            callback();
        }
    }

    // Close the modal when the user clicks on the close button
    document.getElementById('modal-close').onclick = function() {
        document.getElementById('modal').style.display = 'none';
    }
</script>