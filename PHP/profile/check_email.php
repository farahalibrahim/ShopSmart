<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the email from the POST data
$email = $_POST['email'];

$sql = "SELECT * FROM `user` WHERE email = ?";
$stmt = DatabaseHelper::runQuery($conn, $sql, [$email]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the email exists
$emailUsed = $stmt->rowCount() > 0;

// Return a JSON response
echo json_encode(array('emailUsed' => $emailUsed));
// $emailUsed