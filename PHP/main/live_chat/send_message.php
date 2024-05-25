<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
session_start();
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
$message = $_POST['message'];

$ticket_id = $_SESSION['ticket_id'];
$user_id = $_COOKIE['user_id'];

if ($message && $ticket_id && $user_id) {
    // Insert the new message
    $sql = "INSERT INTO messages (ticket_id, sender_id, content) VALUES (:ticket_id, :sender_id, :content)";
    $stmt = DatabaseHelper::runQuery($conn, $sql, [':ticket_id' => $ticket_id, ':sender_id' => $user_id, ':content' => $message]);

    echo 'message_sent';
} else {
    echo 'message_not_sent';
}
