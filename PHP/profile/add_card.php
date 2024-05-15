<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';

// Connect to the database
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the POST data
$user_id = $_POST['user_id'];
$card_number = $_POST['card_number'];
$card_holder_name = $_POST['card_holder_name'];
$expiry_date = $_POST['expiry_date'];
$cvv = $_POST['cvv'];
$balance = $_POST['balance'];

// Check if a card with the same number for the same user id exists
$sql = 'SELECT * FROM `credit card` WHERE user_id = :user_id AND `number` = :card_number';
$stmt = DatabaseHelper::runQuery($conn, $sql, [':user_id' => $user_id, ':card_number' => $card_number]);
$card = $stmt->fetch(PDO::FETCH_ASSOC);

if ($card) {
    // Card already exists
    echo json_encode(['success' => false, 'message' => 'Card already exists']);
} else {
    // Add the card
    $sql = 'INSERT INTO `credit card` (user_id, `number`, name_on_card, expiry, cvv, balance) VALUES (:user_id, :card_number, :card_holder_name, :expiry_date, :cvv, :balance)';
    $stmt = DatabaseHelper::runQuery($conn, $sql, [
        ':user_id' => $user_id,
        ':card_number' => $card_number,
        ':card_holder_name' => $card_holder_name,
        ':expiry_date' => $expiry_date,
        ':cvv' => $cvv,
        ':balance' => $balance
    ]);

    echo json_encode(['success' => true]);
}
