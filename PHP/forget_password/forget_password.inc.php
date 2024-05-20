<?php include_once '../responseModal.inc.php'; ?>


<!-- Forget Password Modal -->
<div id="forgetModal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%;">
        <h3>Forget Password</h3>
        <button id="verifyPhone">Verify by Phone</button>
        <button id="verifyEmail">Verify by Email</button>
        <div id="verificationDiv" style="display: none;">
            <input type="text" id="verificationCode" placeholder="Enter Verification Code">
            <button id="verifyButton">Verify</button>
        </div>
    </div>
</div>

<!-- New Password Modal -->
<div id="passwordModal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%;">
        <input type="password" id="newPassword" placeholder="Enter New Password">
        <input type="password" id="repeatPassword" placeholder="Repeat New Password">
        <button id="updatePassword">Update Password</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        var verificationCode;

        // Open the modal when the a tag is clicked
        $(document).on('click', '#forget_password', function() {
            $('#forgetModal').show();
        });

        // Send a verification code when the phone or email button is clicked
        $('#verifyPhone, #verifyEmail').click(function() {
            verificationCode = Math.floor(Math.random() * (999999 - 100000 + 1)) + 100000;
            // Send the verification code to the phone number or email address saved in the database
            $.post('forget_verification_code.php', {
                type: this.id === 'verifyPhone' ? 'phone' : 'email', // Check if the button clicked is the phone or email button and set type accordingly
                verificationCode: verificationCode
            });
            // Show the verification code input field after sending the verification code
            $('#verificationDiv').show();
        });

        // Check the verification code when the verify button is clicked
        $('#verifyButton').click(function() {
            if ($('#verificationCode').val() == verificationCode) {
                $('#forgetModal').hide();
                $('#passwordModal').show();
            } else {
                showResponseModal('Invalid verification code');
            }
        });

        // Check the new password and repeat password when the update password button is clicked
        $('#updatePassword').click(function() {
            if ($('#newPassword').val() === $('#repeatPassword').val()) {
                // Update the user's password in the database
                $.post('update_password.php', {
                    newPassword: $('#newPassword').val()
                }, function(response) {
                    if (response === 'success') {
                        $('#passwordModal').hide();
                        showResponseModal('Your password has been updated successfully');
                    } else {
                        showResponseModal('Failed to update password');
                    }
                });
            } else {
                showResponseModal('New password and repeat password do not match');
            }
        });
    });
</script>