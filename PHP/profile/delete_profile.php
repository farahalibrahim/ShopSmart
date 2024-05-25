<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the cookie
    $userId = $_COOKIE['user_id'];

    // dletes all orders/shipments/creditcards for the user
    $sql = "DELETE FROM `user` WHERE id = :user;";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user' => $userId]);

    // Delete the cookie
    setcookie('user_id', '', time() - 3600);

    // Indicate success
    $response['success'] = true;
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
