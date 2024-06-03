<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
$user_id = $_COOKIE['user_id'];
$sql = "SELECT * FROM `user` WHERE id = :user";
$user = DatabaseHelper::runQuery($conn, $sql, ['user' => $user_id])->fetchAll(PDO::FETCH_ASSOC)[0];
// print_r($user);
?>

<?php include '../responseModal.inc.php'; ?>
<!-- for forget password -->
<?php include '../forget_password/forget_password.inc.php';
?>
<div class="header">
    <h1>Settings</h1>
</div>
<div class="settings-card">
    <div class="name">
        <h3>Welcome <?= explode(' ', $user['name'])[0] ?></h3><span class="material-symbols-outlined" data-form="nameForm">edit</span>
        <!-- <button><span class="material-symbols-outlined">change_circle</span>Change</button> -->
    </div>
    <div class="email">
        <h4>Email</h4>
        <p><?= $user['email'] ?></p>
        <button data-form="emailForm"><span class="material-symbols-outlined">edit</span><span>Change</span></button>
    </div>
    <div class="pass">
        <h4>Password</h4>
        <p><?= '********' ?></p>
        <button data-form="passwordForm"><span class="material-symbols-outlined">edit</span><span>Change</span></button>
    </div>
    <div class="phone">
        <h4>Phone</h4>
        <p><?= $user['phone'] ?></p>
        <button data-form="phoneForm"><span class="material-symbols-outlined">edit</span><span>Change</span></button>
    </div>
    <div class="delete">
        <!-- <h4>Email</h4> -->
        <!-- <p><?= $user['email'] ?></p> -->
        <button data-form="deleteForm" class="delete_account"><span class="material-symbols-outlined">person_remove</span><span>Delete Account</span></button>
    </div>
</div>
<div id="verificationModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <form id="verificationForm">
            <h2 class="modal_header">Verification</h2>
            <p id="verifyEmail">Verification code was sent to: <?= $user['email'] ?></p>
            <p id="verifyPhone">Verification code was sent to: <?= $user['phone'] ?></p>

            <!-- <label for="verificationCode">Verification Code</label> -->

            <div class="inputDiv">
                <input type="text" id="verificationCode" name="verificationCode" placeholder="xxxxxx" required><br>
            </div>
            <button id="verifyButtonEmail" type="submit">Verify</button>
            <button id="verifyButtonPassword" type="submit">Verify</button>
            <button id="verifyButtonPhone" type="submit">Verify</button>
        </form>
        <p id="verif_status"></p>
    </div>
</div>
<div id="userModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <form id="nameForm" class="modal-form">
            <h2 class="form_header">Change Your Name</h2>
            <div class="inputDiv">
                <!-- <label for="firstname">First Name</label> -->
                <input type="text" id="firstname" name="firstname" value="<?= explode(' ', $user['name'])[0] ?>"><br>
            </div>
            <div class="inputDiv">
                <!-- <label for="lastname">Last Name</label> -->
                <input type="text" id="lastname" name="lastname" value="<?= explode(' ', $user['name'])[1] ?>"><br>
            </div>
            <div class="save_button"><button class="saveButton" type="submit">Save</button>
            </div>
        </form>
        <form id="emailForm" class="modal-form">
            <h2 class="form_header">Change Your Email</h2>
            <div class="inputDiv">
                <!-- <label for="email">Email</label> -->
                <input type="email" id="email" name="email" value="<?= $user['email'] ?>" placeholder="example@example.com"><br>
            </div>
            <p id="email_status"></p>
            <div class="save_button"><button class="saveButton" type="submit">Save</button></div>
            <!-- verify -->
        </form>
        <form id="passwordForm" class="modal-form">
            <h2 class="form_header">Change Your Password</h2>
            <!-- Old Password field -->
            <div class="oldPasswordDiv">
                <!-- <label for="oldPassword">Current Password:</label> -->
                <input type="password" id="oldPassword" name="oldPassword" placeholder="Current Password"><br>
                <button class="pass_visibility" type="button" onclick="togglePasswordVisibility('oldPassword')"><span class="material-symbols-outlined" id="oldPasswordIcon">visibility</span></button><br>
            </div>

            <!-- Repeat Old Password field -->
            <div class="repeatOldPasswordDiv">
                <!-- <label for="repeatOldPassword">Repeat Password:</label> -->
                <input type="password" id="repeatOldPassword" name="repeatOldPassword" placeholder="Repeat Current Password"><br>
                <button class="pass_visibility" type="button" onclick="togglePasswordVisibility('repeatOldPassword')"><span class="material-symbols-outlined" id="repeatOldPasswordIcon">visibility</span></button><br>
            </div>

            <!-- New Password field -->
            <div class="newPasswordDiv">
                <!-- <label for="newPassword">New Password:</label> -->
                <input type="password" id="pass" name="newPassword" placeholder="New Password"><br>
                <button class="pass_visibility" type="button" onclick="togglePasswordVisibility('pass')"><span class="material-symbols-outlined" id="passIcon">visibility</span></button><br>
            </div>

            <!-- Paragraph for status -->
            <p id="pass_status"></p>

            <!-- Save button -->
            <div class="save_button"><button type="submit" class="saveButton">Save</button></div>
        </form>
        <form id="phoneForm" class="modal-form">
            <h2 class="form_header">Change Your Phone Number</h2>
            <div class="inputDiv">
                <input type="text" id="phone" name="phone" placeholder="Enter your phone number">
            </div>
            <p class="status"></p>
            <div class="save_button"><button class="saveButton" type="submit">Save</button></div>
        </form>
        <form id="deleteForm" class="modal-form">
            <!-- Delete confirmation -->
            <h2 class="warning_header">This action cannot be undone!</h2>
            <p class="warning_details">Are you sure you want to delete your account?</p>
            <div class="save_button"><button class=" delete_account" type="submit">Yes, delete my account</button></div>
        </form>
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
    // Dynamic validation of new password
    window.onload = function() {
        var newPasswordInput = document.getElementById('pass');
        var passStatus = document.getElementById('pass_status');

        newPasswordInput.addEventListener('input', function() {
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/; // At least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long

            if (passwordRegex.test(newPasswordInput.value)) {
                passStatus.textContent = '';
            } else {
                passStatus.textContent = 'Must have 1 upper, 1 lower, 1 digit, min 8 chars';
                passStatus.style.color = 'red';
            }
        });
    }
    // Modal script for name,email,password,phone,delete forms
    $(document).ready(function() {
        // When the user clicks the button/edit icon, open the modal 
        $('.settings-card button, .settings-card span').click(function() {
            var formId = $(this).data('form');
            $('.modal-form').hide();
            $('#' + formId).show();
            $('#userModal').show();

            // Add 'required' attribute to all input fields in the displayed form
            $('#' + formId + ' input').attr('required', true);
        });

        // When the user clicks on <span> (x), close the modal
        $('#userModal #closeModalButton').click(function() {
            $('#userModal').hide();
        });

        // When the user clicks anywhere outside of the modal, close it
        $(window).click(function(event) {
            if (event.target == document.getElementById('userModal')) {
                $('#userModal').hide();
            }
        });
    });

    // dynamic email input regex
    $(document).ready(function() {
        $('#email').on('input', function() {
            var email = $(this).val();
            var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            // if (email === '') {
            //     $('#email_status').text('Cannot be empty');
            //     $('#email_status').css('color', 'red');
            // } else 
            if (emailRegex.test(email)) {
                $('#email_status').text('');
            } else {
                $('#email_status').text('Invalid email, yourname@example.com');
                $('#email_status').css('color', 'red');
            }
        });
    });

    // email verification
    $(document).ready(function() {
        // check if email is used
        $('#emailForm').submit(function(e) {
            e.preventDefault();
            $('#verifyButtonEmail').show();
            $('#verifyButtonPassword').hide();
            $('#verifyButtonPhone').hide();

            $('#verifyEmail').text('Verification code was sent to: ' + $('#email').val());
            $('#verifyPhone').hide();

            var email = $('#email').val();
            var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailRegex.test(email)) {
                // If the email does not match the regex, display an alert
                $('#userModal').hide();
                showResponseModal('Invalid email format');
                return;
            }

            $.post('check_email.php', {
                email: email,
                type: 'email'
            }, function(response) {
                if (response.emailUsed) {
                    // If the email is used, display an alert
                    $('#userModal').hide();
                    showResponseModal('This email is already used');
                } else {
                    // If the email is not used, proceed with the verification
                    $.post('verify.php', {
                        email: email,
                        type: 'email'
                    }, function(verificationCode) {
                        // Show the verification modal
                        $('#verificationModal').show();
                        $('#userModal').hide();

                        // Store the verification code in a data attribute
                        $('#verifyButtonEmail').data('verificationCode', verificationCode);
                    });
                }
            }, 'json');
        });


        // modal for email verification
        $('#verifyButtonEmail').off('click').click(function() {
            var verificationCode = $(this).data('verificationCode');

            if ($('#verificationCode').val() === verificationCode) {
                // If the verification code is correct, send an AJAX request to verify.php to update the email
                $.post('verify.php', {
                    email: $('#email').val(),
                    verificationCode: verificationCode,
                    type: 'email'
                }, function(response) {
                    if (response === 'Your email is updated successfully!') {
                        $('#verificationModal').hide();
                        // Display success message in the paragraph
                        showResponseModal('Your email is updated successfully!', function() {
                            $('#settings').click();
                            // Disable the input and verify button
                            // $('#verificationCode').prop('disabled', true);
                            // $('#verifyButton').prop('disabled', true);
                        });
                    } else {
                        // If the email change was not successful, display error message in the paragraph
                        $('#verificationModal').hide();
                        showResponseModal('Email change failed', function() {
                            $('#settings').click();
                        });
                    }
                });
            } else {
                // If the verification code is incorrect, display error message in the paragraph
                $('#verif_status').text('Invalid verification code');
                $('#verif_status').css('color', 'red');
            }
        });
    });

    // password verification
    $(document).ready(function() {
        $('#passwordForm').submit(function(e) {
            e.preventDefault();
            $('#verifyButtonPassword').show();
            $('#verifyButtonPhone').hide();
            $('#verifyButtonEmail').hide();

            var userEmail = "<?php echo $user['email']; ?>";
            $('#verifyEmail').text('Verification code was sent to: ' + userEmail);
            $('#verifyPhone').hide();

            var oldPassword = $('#oldPassword').val();
            var repeatOldPassword = $('#repeatOldPassword').val();
            var newPassword = $('#pass').val();
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/; // At least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long

            console.log(newPassword);
            if (!passwordRegex.test(newPassword)) {
                // If the password does not match the regex, display an alert
                $('#userModal').hide();
                showResponseModal('Must have 1 upper, 1 lower, 1 digit, min 8 chars', function() {
                    $('#userModal').show();
                });
                return;
            }

            if (oldPassword === repeatOldPassword) {
                $.post('check_password.php', {
                    oldPassword: oldPassword
                }, function(response) {
                    if (response === 'match') {
                        $.post('verify.php', {
                            type: 'password',
                            password: newPassword
                        }, function(verificationCode) {
                            // Show the verification modal
                            $('#verificationModal').show();
                            $('#userModal').hide();

                            // Store the verification code in a data attribute
                            $('#verifyButtonPassword').data('verificationCode', verificationCode);
                        });
                    } else {
                        $('#pass_status').html('Old password is wrong! <a id="forget_password" href="#">Reset Password</a>').css('color', 'red');
                    }
                });
            } else {
                $('#pass_status').text('Old password and repeat old password do not match').css('color', 'red');
            }
        });

        $('#verifyButtonPassword').off('click').click(function() {
            var verificationCode = $(this).data('verificationCode');

            if ($('#verificationCode').val() === verificationCode) {
                // If the verification code is correct, send an AJAX request to verify.php to update the password
                $.post('verify.php', {
                    password: $('#newPassword').val(),
                    verificationCode: verificationCode,
                    type: 'password'
                }, function(response) {
                    if (response === 'Your password is updated successfully!') {
                        $('#verificationModal').hide();
                        // Display success message in the paragraph
                        showResponseModal('Your password is updated successfully!', function() {
                            $('#settings').click();
                            // Disable the input and verify button
                            // $('#verificationCode').prop('disabled', true);
                            // $('#verifyButtonPassword').prop('disabled', true);
                        });
                    } else {
                        // If the password change was not successful, display error message in the paragraph
                        $('#verificationModal').hide();
                        showResponseModal('Password change failed', function() {
                            $('#settings').click();
                        });
                    }
                });
            } else {
                // If the verification code is incorrect, display error message in the paragraph
                $('#verif_status').text('Invalid verification code');
                $('#verif_status').css('color', 'red');
            }
        });
    });

    // auto-format phone input contents based on number format for db contents for phone
    $(document).ready(function() {
        $('#phone').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, '');
            if (number.length > 11) {
                number = number.slice(0, 11);
            }
            if (number.length == 7) {
                number = number.replace(/(\d{3})(\d{4})/, "$1-$2"); // 123-4567 format
            } else if (number.length == 10) {
                number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3"); // (123) 456-7890 format
            } else if (number.length == 11) {
                number = number.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, "$1-$2-$3-$4"); // 1-123-456-7890 format
            }
            $(this).val(number);
        });

        $('#phone').on('keypress', function(e) {
            if (e.which < 48 || e.which > 57) { //prevent other than number 0-9
                e.preventDefault();
            }
        });
    });

    // phone verification
    $(document).ready(function() {
        // check if phone is used
        $('#phoneForm').submit(function(e) {
            e.preventDefault();
            $('#verifyButtonPhone').show();
            $('#verifyButtonPassword').hide();
            $('#verifyButtonEmail').hide();

            $('#verifyEmail').hide();
            $('#verifyPhone').text('Verification code was sent to: ' + $('#phone').val());

            var phone = $('#phone').val();
            // based on number format for db contents for phone
            var phoneRegex = /^1?[-.\s]?\(?(\d{3})\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
            if (!phoneRegex.test(phone)) {
                // If the phone does not match the regex, display an alert
                $('#userModal').hide();
                showResponseModal('Invalid phone format');
                return;
            }

            $.post('check_phone.php', {
                phone: phone,
                type: 'phone'
            }, function(response) {
                if (response.phoneUsed) {
                    // If the phone is used, display an alert
                    $('#userModal').hide();
                    showResponseModal('This phone number is already used');
                } else {
                    // If the phone is not used, proceed with the verification
                    $.post('verify.php', {
                        phone: phone,
                        type: 'phone'
                    }, function(verificationCode) {
                        // Show the verification modal
                        $('#verificationModal').show();
                        $('#userModal').hide();

                        // Store the verification code in a data attribute
                        $('#verifyButtonPhone').data('verificationCode', verificationCode);
                    });
                }
            }, 'json');
        });

        // modal for phone verification
        $('#verifyButtonPhone').off('click').click(function() {
            var verificationCode = $(this).data('verificationCode');

            if ($('#verificationCode').val() === verificationCode) {
                // If the verification code is correct, send an AJAX request to verify.php to update the phone
                $.post('verify.php', {
                    phone: $('#phone').val(),
                    verificationCode: verificationCode,
                    type: 'phone'
                }, function(response) {
                    if (response === 'Your phone number is updated successfully!') {
                        $('#verificationModal').hide();
                        // Display success message in the paragraph
                        showResponseModal('Your phone number is updated successfully!', function() {
                            $('#settings').click();
                        });
                    } else {
                        // If the phone change was not successful, display error message in the paragraph
                        $('#verificationModal').hide();
                        showResponseModal('Phone number change failed', function() {
                            $('#settings').click();
                        });
                    }
                });
            } else {
                // If the verification code is incorrect, display error message in the paragraph
                $('#verif_status').text('Invalid verification code');
                $('#verif_status').css('color', 'red');
            }
        });
    });

    // name change
    $(document).ready(function() {
        $('#nameForm .saveButton').click(function(e) {
            e.preventDefault();

            var firstname = $('#nameForm #firstname').val(); // replace '#name' with the actual id of your name input field
            var lastname = $('#nameForm #lastname').val(); // replace '#name' with the actual id of your name input field

            $.ajax({
                url: 'update_name.php', // replace with the path to your PHP script
                type: 'POST',
                data: {
                    id: <?= $user_id ?>,
                    firstname: firstname,
                    lastname: lastname,
                },
                success: function(response) {
                    console.log(response);
                    $('#userModal').hide();
                    showResponseModal(response, function() {
                        $('#settings').click();
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // handle error
                    console.error(textStatus, errorThrown);
                    $('#userModal').hide();
                    showResponseModal(errorThrown, function() {
                        $('#settings').click();
                    });
                }
            });
        });
    });

    // delete account
    $('#deleteForm').submit(function(event) {
        event.preventDefault();

        $.post('delete_profile.php', function(data) {
            if (data.success) {
                // If the profile was successfully deleted, redirect to the main index page
                window.location.href = '../main/index.php';
            } else {
                // If there was an error, display it in the response modal
                showResponseModal('Error: Profile could not be deleted.');
            }
        }, 'json');
    });

    // verification modal settings
    $(document).ready(function() {
        // Prevent the verification form from being submitted in the traditional way
        $('#verificationForm').submit(function(e) {
            e.preventDefault();
        });

        // When the user clicks on <span> (x), close the modal
        $('#verificationModal #closeModalButton').click(function() {
            $('#verificationModal').hide();
            $('#settings').click();

        });

        // When the user clicks anywhere outside of the modal, close it
        $(window).click(function(event) {
            if (event.target == document.getElementById('verificationModal')) {
                $('#verificationModal').hide();
                event.stopPropagation();
                $('#settings').click();
            }
        });
    });
</script>