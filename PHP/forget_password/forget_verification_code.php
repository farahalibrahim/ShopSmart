<?php
$type = $_POST['type'];
$verificationCode = $_POST['verificationCode'];

if ($_POST['type'] === 'email') {
    // Send the verification code to the new email address
    // mail($_POST['email'], 'Verification Code', 'Your verification code is: ' . $verificationCode);
} else if ($_POST['type'] === 'phone') {
    // Send the verification code to the new phone number, needs SMS gateway to send the SMS
    // sms_send($_POST['phone'], 'Your verification code is: ' . $verificationCode);
}
// Return the verification code for testing purposes
echo $verificationCode;
