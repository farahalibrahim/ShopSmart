<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$sql = "SELECT freeze_expiry FROM user WHERE id = :id";
$stmt = DatabaseHelper::runQuery($conn, $sql, array(":id" => $_POST['user_id']));
$freeze = $stmt->fetchColumn();

if ($freeze != NULL) {
    echo $freeze;
} else {
    echo 'Not frozen';
}
