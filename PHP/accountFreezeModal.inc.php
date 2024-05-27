<div id="modal" style="display: none; justify-content:center; align-items:center; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width:800px; border-radius:20px;">
        <span id="modal-close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold;">&times;</span>
        <h3 style="text-align: center;" id="modal-text"></h3>
        <p style="text-align: center;" id="modal-freeze-expiry"></p>
        <p style="text-align: center;">If you believe this is a mistake, reach us out at <a href="mailto:shopsmart.help@gmail.com">shopsmart.help@gmail.com</a></p>
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
        document.getElementById('modal').style.display = 'flex';
        if (typeof callback === 'function') {
            callback();
        }
    }

    // Close the modal when the user clicks on the close button
    document.getElementById('modal-close').onclick = function() {
        document.getElementById('modal').style.display = 'none';
    }
</script>