<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the cookie
    $userId = $_COOKIE['user_id'];

    // Prepare a statement to delete the user
    $sql = "DELETE FROM `shipment` WHERE id = :user;
            DELETE FROM `order_detail` WHERE id = :user;
            DELETE FROM `order` WHERE id = :user;
            DELETE FROM `user` WHERE id = :user;";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user' => $userId]);

    // Delete the cookie
    setcookie('user_id', '', time() - 3600);

    // Redirect to index.php
    header('Location: index.php');
    exit;
}
