<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the email from the POST data
$phone = $_POST['phone'];

$sql = "SELECT * FROM `user` WHERE phone = ?";
$stmt = DatabaseHelper::runQuery($conn, $sql, [$phone]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the email exists
$phoneUsed = $stmt->rowCount() > 0;

// Return a JSON response
echo json_encode(array('phoneUsed' => $phoneUsed));
