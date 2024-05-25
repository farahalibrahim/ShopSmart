<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
// Connect to the database
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the user ID from the cookie
$user_id = $_COOKIE['user_id'];

// Get the new address from the POST data
$street_address = $_POST['street_address'];
$city = $_POST['city'];

// Prepare an SQL statement to update the user's address
$sql = "UPDATE `user` SET street_address = :street_address, city = :city WHERE id = :user";

// Run the query, passing in the new address and the user ID
$result = DatabaseHelper::runQuery($conn, $sql, ['street_address' => $street_address, 'city' => $city, 'user' => $user_id]);

// Check if the query was successful
if ($result) {
    // If it was, send a JSON response indicating success
    echo json_encode(['success' => true]);
} else {
    // If it wasn't, send a JSON response indicating failure
    echo json_encode(['success' => false]);
}
