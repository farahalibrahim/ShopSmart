<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
session_start();
$sql = "SELECT status FROM tickets WHERE id = :ticket_id";

$stmt = DatabaseHelper::runQuery($conn, $sql, [':ticket_id' => $_SESSION['ticket_id']]);
// Fetch the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['status'] === 'closed') {
    // Unset the session variable and destroy the session
    unset($_SESSION['ticket_id']);
    session_destroy();

    echo 'closed';
}
