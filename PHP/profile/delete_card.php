<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$card = $_POST['card'];
$user = $_POST['user'];

$query = "DELETE FROM `credit card` WHERE number = :card AND user_id = :user";
$stmt = DatabaseHelper::runQuery($conn, $query, ['card' => $card, 'user' => $user]);

if ($stmt) {
    echo "Card deleted successfully";
} else {
    echo "Error deleting card";
}
