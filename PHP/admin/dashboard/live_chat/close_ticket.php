<?php
include_once('../../../connection.inc.php');
include_once('../../../dbh.class.inc.php');

$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if (isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];

    $sql = "UPDATE tickets SET status = 'closed' WHERE id = :ticket_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, [':ticket_id' => $ticket_id]);

    echo 'Ticket closed';
} else {
    echo 'No ticket ID provided';
}
