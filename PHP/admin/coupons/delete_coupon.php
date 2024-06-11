<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$coupon = $_POST['coupon'];

$sql = "DELETE FROM `coupon code` WHERE coupon = :coupon";
$attr = ['coupon' => $coupon];
$stmt = DatabaseHelper::runQuery($conn, $sql, $attr);

echo "Coupon deleted successfully";
