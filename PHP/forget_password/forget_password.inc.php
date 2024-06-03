<?php //include_once '../responseModal.inc.php'; 
?>


<!-- Forget Password Modal -->
<div id="forgetModal" style="display: none; position: fixed; z-index: 10; left: 0; top: 0; width: 100%;  height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width:800px; border-radius:30px;">
        <h3>Forget Password</h3>
        <div class="verifyMethod" style="display: flex; justify-content: center;">
            <button id="forget_verifyPhone" style="margin-left: 0;">Verify by Phone</button>
            <button id="forget_verifyEmail" style="margin-left: 0;">Verify by Email</button>
        </div>
        <div id="verificationDiv" style="display: none;">
            <input type="text" id="forget_verificationCode" placeholder="Enter Verification Code">
            <button id="verifyButton">Verify</button>
        </div>
    </div>
</div>

<!-- New Password Modal -->
<div id="passwordModal" style="display: none; position: fixed; z-index: 10; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width:800px; border-radius:30px;">
        <h3>Enter New Password</h3>
        <div class="forget_newpassword">
            <input type="password" id="newPassword" placeholder="Enter New Password">
            <button class="pass_visibility" type="button" onclick="togglePasswordVisibility('newPassword')"><span class="material-symbols-outlined" id="newPasswordIcon">visibility</span></button><br>

        </div>
        <div class="forget_repeatpassword">
            <input type="password" id="repeatPassword" placeholder="Repeat New Password">
            <button class="pass_visibility" type="button" onclick="togglePasswordVisibility('repeatPassword')"><span class="material-symbols-outlined" id="repeatPasswordIcon">visibility</span></button><br>

        </div>
        <button id="updatePassword">Update Password</button>
    </div>
</div>

<script>
    // Function to toggle password visibility
    function togglePasswordVisibility(id) {
        var passwordInput = document.getElementById(id);
        var icon = document.getElementById(id + 'Icon');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.textContent = "visibility_off";
        } else {
            passwordInput.type = "password";
            icon.textContent = "visibility";
        }
    }
    $(document).ready(function() {
        var verificationCode;

        // Open the modal when the a tag is clicked
        $(document).on('click', '#forget_password', function() {
            $('#forgetModal').show();
        });

        // Send a verification code when the phone or email button is clicked
        $('#forget_verifyPhone, #forget_verifyEmail').click(function() {
            verificationCode = Math.floor(Math.random() * (999999 - 100000 + 1)) + 100000;
            console.log(verificationCode);
            // Send the verification code to the phone number or email address saved in the database
            $.post('http://localhost:3000/PHP/forget_password/forget_verification_code.php', {
                type: this.id === 'forget_verifyPhone' ? 'phone' : 'email', // Check if the button clicked is the phone or email button and set type accordingly
                verificationCode: verificationCode
            });
            // Show the verification code input field after sending the verification code
            $('#verificationDiv').show();
        });

        // Check the verification code when the verify button is clicked
        $('#verifyButton').click(function() {
            if ($('#forget_verificationCode').val() == verificationCode) {
                $('#forgetModal').hide();
                $('#passwordModal').show();
            } else {
                showResponseModal('Invalid verification code');
            }
        });

        // Check the new password and repeat password when the update password button is clicked
        $('#updatePassword').click(function() {
            if ($('#newPassword').val() === $('#repeatPassword').val()) {
                var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/; // At least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long

                if (!passwordRegex.test($('#newPassword').val())) {
                    // If the password does not match the regex, display an alert
                    $('#passwordModal').hide();
                    showResponseModal('Must have 1 upper, 1 lower, 1 digit, min 8 chars', function() {
                        $('#passwordModal').show();
                    });
                    return;
                }
                if ($('#email').length) {
                    var email = $('#email').val();
                } else {
                    var email = "";
                }
                // Update the user's password in the database
                $.post('http://localhost:3000/PHP/forget_password/update_password.php', {
                    newPassword: $('#newPassword').val(),
                    email: email
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

    // When the user clicks anywhere outside of the modal, close it
    $(window).click(function(event) {
        if (event.target == document.getElementById('forgetModal')) {
            $('#forgetModal').hide();
            event.stopPropagation();
        }
        if (event.target == document.getElementById('passwordModal')) {
            $('#passwordModal').hide();
            event.stopPropagation();
        }
    });
</script>
<style>
    .forget_newpassword input,
    .forget_repeatpassword input {
        flex-grow: 1;
        height: 40px;
        border: none;
        border-radius: 20px;
        outline: none;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding-right: 50px;
        padding: 0 20px;
        position: relative;
    }

    #forget_verificationCode {
        flex-grow: 1;
        width: 100%;
        height: 40px;
        border: none;
        border-radius: 20px;
        outline: none;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding-right: 50px;
        padding: 0 20px;
        margin-block: 10px;
    }

    .pass_visibility span {
        font-size: 20px;
        color: #ccc;
    }

    .pass_visibility {
        cursor: pointer;
        background-color: transparent;
        color: black;
        margin: 0%;
        padding: 0%;
    }

    .forget_newpassword button,
    .forget_repeatpassword button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .forget_newpassword,
    .forget_repeatpassword {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
    }

    button:hover {
        border: green 2px solid;
        background-color: white;
        color: black;
    }

    button {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: green;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 70px;
        margin-right: 10px;
        cursor: pointer;
    }

    button {
        margin-left: auto;
    }
</style>