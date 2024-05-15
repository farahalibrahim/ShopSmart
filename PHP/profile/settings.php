<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user_id = $_COOKIE['user_id'];
$sql = "SELECT * FROM `user` WHERE id = :user";
$user = DatabaseHelper::runQuery($conn, $sql, ['user' => $user_id])->fetchAll(PDO::FETCH_ASSOC)[0];
print_r($user);
?>
<div class="header">
    <h1>Settings</h1>
</div>
<div class="settings-card">
    <div class="email">
        <h3>Welcome <?= explode(' ', $user['name'])[0] ?></h3><span class="material-symbols-outlined" data-form="nameForm">edit</span>
        <!-- <button><span class="material-symbols-outlined">change_circle</span>Change</button> -->
    </div>
    <div class="email">
        <h4>Email</h4>
        <p><?= $user['email'] ?></p>
        <button data-form="emailForm"><span class="material-symbols-outlined">edit</span>Change</button>
    </div>
    <div class="pass">
        <h4>Password</h4>
        <p><?= '********' ?></p>
        <button data-form="passwordForm"><span class="material-symbols-outlined">edit</span>Change</button>
    </div>
    <div class="phone">
        <h4>Phone</h4>
        <p><?= $user['phone'] ?></p>
        <button data-form="phoneForm"><span class="material-symbols-outlined">edit</span>Change</button>
    </div>
    <div class="delete_account">
        <!-- <h4>Email</h4> -->
        <!-- <p><?= $user['email'] ?></p> -->
        <button data-form="deleteForm"><span class="material-symbols-outlined">person_remove</span>Delete Account</button>
    </div>
</div>
<div id="verificationModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <form id="verificationForm">
            <label for="verificationCode">Verification Code</label>
            <input type="text" id="verificationCode" name="verificationCode" required><br>
            <button id="verifyButton" type="submit">Verify</button>
        </form>
        <p id="status"></p>
    </div>
</div>
<div id="userModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <form id="nameForm" class="modal-form">
            <!-- Name form fields -->
            <h2>Change Your Name</h2>
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value="<?= explode(' ', $user['name'])[0] ?>"><br>
            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="<?= explode(' ', $user['name'])[1] ?>"><br>
            <button type="submit">Save</button>
        </form>
        <form id="emailForm" class="modal-form">
            <!-- Email form fields -->
            <h2>Change Your Email</h2>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= $user['email'] ?>"><br>
            <button type="submit">Save</button>
            <!-- verify -->
        </form>
        <form id="passwordForm" class="modal-form">
            <!-- Password form fields -->
        </form>
        <form id="phoneForm" class="modal-form">
            <!-- Phone form fields -->
        </form>
        <form id="deleteForm" class="modal-form">
            <!-- Delete confirmation -->
            <h2 class="warning">This action cannot be undone!</h2>
            <p>Are you sure you want to delete your account?</p>
            <button type="submit">Yes, delete my account</button>
        </form>
    </div>
</div>
<script>
    // Modal script for name,email,password,phone,delete
    $(document).ready(function() {
        // When the user clicks the button/edit icon, open the modal 
        $('.settings-card button, .settings-card span').click(function() {
            var formId = $(this).data('form');
            $('.modal-form').hide();
            $('#' + formId).show();
            $('#userModal').show();
        });

        // When the user clicks on <span> (x), close the modal
        $('#closeModalButton').click(function() {
            $('#userModal').hide();
        });

        // When the user clicks anywhere outside of the modal, close it
        $(window).click(function(event) {
            if (event.target == document.getElementById('userModal')) {
                $('#userModal').hide();
            }
        });
    });

    // email verification
    $(document).ready(function() {
        // check if email is used
        $('#emailForm').submit(function(e) {
            e.preventDefault();

            $.post('check_email.php', {
                email: $('#email').val(),
                type: 'email'
            }, function(response) {
                if (response.emailUsed) {
                    // If the email is used, display an alert
                    alert('This email is already used');
                } else {
                    // If the email is not used, proceed with the verification
                    $.post('verify.php', {
                        email: $('#email').val()
                    }, function(verificationCode) {
                        // Show the verification modal
                        $('#verificationModal').show();
                        $('#userModal').hide();

                        // Store the verification code in a data attribute
                        $('#verifyButton').data('verificationCode', verificationCode);
                    });
                }
            }, 'json');
        });

        // modal for email verification
        $('#verifyButton').off('click').click(function() {
            var verificationCode = $(this).data('verificationCode');

            if ($('#verificationCode').val() === verificationCode) {
                // If the verification code is correct, send an AJAX request to verify.php to update the email
                $.post('verify.php', {
                    email: $('#email').val(),
                    verificationCode: verificationCode,
                    type: 'email'
                }, function(response) {
                    if (response === 'Email change successful') {
                        // Display success message in the paragraph
                        $('#status').text('Email change successful');

                        // Disable the input and verify button
                        $('#verificationCode').prop('disabled', true);
                        $('#verifyButton').prop('disabled', true);
                    } else {
                        // If the email change was not successful, display error message in the paragraph
                        $('#status').text('Email change failed');
                    }
                });
            } else {
                // If the verification code is incorrect, display error message in the paragraph
                $('#status').text('Invalid verification code');
            }
        });

        // Prevent the verification form from being submitted in the traditional way
        $('#verificationForm').submit(function(e) {
            e.preventDefault();
        });

        // When the user clicks on <span> (x), close the modal
        $('#closeModalButton').click(function() {
            $('#verificationModal').hide();
        });

        // When the user clicks anywhere outside of the modal, close it
        // $(window).click(function(event) {
        //     if (event.target == document.getElementById('verificationModal')) {
        //         $('#verificationModal').hide();
        //         event.stopPropagation();
        //         // $('#settings').click();
        //     }
        // });
    });
</script>