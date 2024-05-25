<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the ticket ID from the POST data
$ticket_id = $_POST['ticket_id'];

// Query the database to get the user's name and email
$query = "SELECT user.name, user.email FROM tickets JOIN user ON tickets.user_id = user.id WHERE tickets.id = ?";
$stmt = DatabaseHelper::runQuery($conn, $query, [$ticket_id]);
$user = $stmt->fetch();

if (!$user) {
    // Print out the error information
    print_r($stmt->errorInfo());
    exit;
}
// Manually assign the values to JSON variables
$jsonResponse = array(
    'name' => $user['name'],
    'email' => $user['email']
);

// Return the user's name and email as a JSON object
header('Content-Type: application/json');
echo json_encode($jsonResponse);
