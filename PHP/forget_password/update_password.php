<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user_id = $_COOKIE['user_id'];

try {
    // Get the new password from the POST data and hash it
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

    // Update the user's password in the database
    $sql = "UPDATE $usersTable SET password = :password WHERE id = :id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['password' => $newPassword, 'id' => $user_id]);

    echo 'success';
} catch (PDOException $e) {
    echo 'error: ' . $e->getMessage();
}
