<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$order = $_POST['order'];

$query = "UPDATE `order` SET `status` = 'packed' WHERE order_nb = :order";
$stmt = DatabaseHelper::runQuery($conn, $query, ['order' => $order]);

$response = ['success' => $stmt->rowCount() > 0];
echo json_encode($response);
