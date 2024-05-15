<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
// session_start();

$couponCode = $_POST['coupon'];

$query = "SELECT discount_percent FROM `coupon code` WHERE coupon = ?";
$stmt = DatabaseHelper::runQuery($conn, $query, [$couponCode]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo $result['discount_percent'];
    setcookie('discount', $result['discount_percent'], time() + 3600, '/');
} else {
    echo 0; // no discount if coupon code is invalid
}
