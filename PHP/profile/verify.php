<?php
session_start();
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !isset($_POST['verificationCode']) || isset($_POST['phone']) && !isset($_POST['verificationCode']) || isset($_POST['password']) && !isset($_POST['verificationCode'])) {
        // Generate a random verification code
        $verificationCode = rand(100000, 999999);

        // Store the verification code in a session variable
        $_SESSION['verificationCode'] = $verificationCode;

        // Check the type of verification
        if ($_POST['type'] === 'email') {
            // Store the new email in a session variable
            $_SESSION['newEmail'] = $_POST['email'];
            // Send the verification code to the new email address
            // mail($_POST['email'], 'Verification Code', 'Your verification code is: ' . $verificationCode);
        } else if ($_POST['type'] === 'phone') {
            // Store the new phone number in a session variable
            $_SESSION['newPhone'] = $_POST['phone'];
            // Send the verification code to the new phone number
            // You'll need to use an SMS gateway to send the SMS
            // sms_send($_POST['phone'], 'Your verification code is: ' . $verificationCode);
        } else if ($_POST['type'] === 'password') {
            // Store the new password in a session variable
            $_SESSION['newPassword'] = $_POST['password'];
            // Send the verification code to the user's email address
            // mail($_POST['email'], 'Verification Code', 'Your verification code is: ' . $verificationCode);
        }

        // Return the verification code for testing purposes
        echo $verificationCode;
    } elseif (isset($_POST['verificationCode'])) {
        // Check if the entered verification code matches the stored code
        if ($_POST['verificationCode'] == $_SESSION['verificationCode']) {
            $userId = $_COOKIE['user_id'];

            // Prepare a statement to update the email, phone, or password
            if ($_POST['type'] === 'email') {
                $newEmail = $_SESSION['newEmail'];
                $sql = "UPDATE `user` SET email = :contact WHERE id = :user";
                $stmt = DatabaseHelper::runQuery($conn, $sql, ['contact' => $newEmail, 'user' => $userId]);
                echo 'Your email is updated successfully!';
            } else if ($_POST['type'] === 'phone') {
                $newPhone = $_SESSION['newPhone'];
                $sql = "UPDATE `user` SET phone = :contact WHERE id = :user";
                $stmt = DatabaseHelper::runQuery($conn, $sql, ['contact' => $newPhone, 'user' => $userId]);
                echo 'Your phone number is updated successfully!';
            } else if ($_POST['type'] === 'password') {
                $newPassword = $_SESSION['newPassword'];
                // Hash the new password before storing it in the database
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "UPDATE `user` SET password = :password WHERE id = :user";
                $stmt = DatabaseHelper::runQuery($conn, $sql, ['password' => $hashedPassword, 'user' => $userId]);
                echo 'Your password is updated successfully!';
            }
        } else {
            // Display an error message
            echo 'Invalid verification code';
        }
    }
}
