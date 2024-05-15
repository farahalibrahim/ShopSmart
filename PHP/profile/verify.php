<?php
session_start();
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !isset($_POST['verificationCode'])) {
        // Generate a random verification code
        $verificationCode = rand(100000, 999999);

        // Store the verification code and email in a session variable
        $_SESSION['verificationCode'] = $verificationCode;
        $_SESSION['newEmail'] = $_POST['email'];

        // Check the type of verification
        if ($_POST['type'] === 'email') {
            // Send the verification code to the new email address
            // mail($_POST['email'], 'Verification Code', 'Your verification code is: ' . $verificationCode);
        } else if ($_POST['type'] === 'phone') {
            // Send the verification code to the new phone number
            // You'll need to use an SMS gateway to send the SMS
            // sms_send($_POST['phone'], 'Your verification code is: ' . $verificationCode);
        }

        // Return the verification code for testing purposes
        echo $verificationCode;
    } elseif (isset($_POST['verificationCode'])) {
        // Check if the entered verification code matches the stored code
        if ($_POST['verificationCode'] == $_SESSION['verificationCode']) {
            // Proceed with the email or phone change
            $newContact = $_SESSION['newEmail'];
            $userId = $_COOKIE['user_id'];

            // Prepare a statement to update the email or phone
            if ($_POST['type'] === 'email') {
                $sql = "UPDATE `user` SET email = :contact WHERE id = :user";
                $stmt = DatabaseHelper::runQuery($conn, $sql, ['contact' => $newContact, 'user' => $userId]);

                echo 'Your email is updated successfully!';
            } else if ($_POST['type'] === 'phone') {
                $sql = "UPDATE `user` SET phone = :contact WHERE id = :user";
                $stmt = DatabaseHelper::runQuery($conn, $sql, ['contact' => $newContact, 'user' => $userId]);

                echo 'Your phone number is updated successfully!';
            }
        } else {
            // Display an error message
            echo 'Invalid verification code';
        }
    }
}
