<?php
// function validate($data)
// {
//     $data = trim($data);
//     $data = stripslashes($data);
//     $data = htmlspecialchars($data);
//     return $data;
// }

// verify_login.php
include_once 'connection.inc.php';
include_once 'dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get POST data
$email = $_POST['email'];
$pass = $_POST['pass'];

$sql = "SELECT * FROM user WHERE email = ?";
$stmt = DatabaseHelper::runQuery($conn, $sql, [$email]);
$user = $stmt->fetch();

// Check if user exists
if (!$user) {
    echo json_encode(['status' => 'error', 'message' => "User doesn't exist, sign up instead."]);
    exit;
}

// Verify password
if (!password_verify($pass, $user['password'])) {
    echo json_encode(['status' => 'error', 'message' => "Wrong password."]);
    exit;
}

setcookie('user_id', $user['id'], time() + 86400, '/');
setcookie('user_name', $user['name'], time() + 86400, '/');

$sql = "UPDATE user SET login_status = 'logged_in' WHERE id = :user";

$stmt = DatabaseHelper::runQuery($conn, $sql, ['user' => $user['id']]);

// Return role
echo json_encode(['status' => 'success', 'role' => $user['role']]);
