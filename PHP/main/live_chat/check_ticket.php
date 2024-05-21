<?php
session_start();

include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if (!isset($_SESSION['ticket_id'])) {
    // Create a new ticket
    $sql = "INSERT INTO tickets (user_id, `timestamp`) VALUES (:user_id, NOW())";
    $stmt = DatabaseHelper::runQuery($conn, $sql, [':user_id' => $_COOKIE['user_id']]);

    // Get the ID of the newly created ticket
    $ticket_id = $conn->lastInsertId();

    // Save the ticket ID to the session
    $_SESSION['ticket_id'] = $ticket_id;

    echo 'ticket_created';
} else {
    echo 'ticket_exists';
}
