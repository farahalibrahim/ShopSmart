<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user_id = null;

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} elseif (isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql = "SELECT id FROM user WHERE email = :email";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $user_id = $user['id'];
    }
}

// if (!$user_id) {
//     header('Location: http://localhost:3000/PHP/login.php');
//     exit;
// }

try {
    // Get the new password from the POST data and hash it
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

    // Update the user's password in the database
    $sql = "UPDATE user SET password = :password WHERE id = :id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['password' => $newPassword, 'id' => $user_id]);

    echo 'success';
} catch (PDOException $e) {
    echo 'error: ' . $e->getMessage();
}
