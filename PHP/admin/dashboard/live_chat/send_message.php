<?php
include_once '../../../connection.inc.php';
include_once '../../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
// session_start();

// Get the POST data
$message = $_POST['message'];
$ticket_id = $_POST['ticket_id']; // Get the ticket ID from the POST data
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
$user_id = $_COOKIE['user_id'];

if ($message && $ticket_id && $user_id) {
    // Insert the new message
    $sql = "INSERT INTO messages (ticket_id, sender_id, content) VALUES (:ticket_id, :sender_id, :content)";
    $stmt = DatabaseHelper::runQuery($conn, $sql, [':ticket_id' => $ticket_id, ':sender_id' => $user_id, ':content' => $message]);

    echo 'message_sent';
} else {
    echo 'message_not_sent';
}
