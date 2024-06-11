<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$coupon = $_POST['coupon'];
$discountPercent = $_POST['discount_percent'];
$expiry = $_POST['expiry'];

$sql = 'SELECT * FROM `coupon code` WHERE coupon = ?';
$stmt = DatabaseHelper::runQuery($conn, $sql, [$coupon]);

if ($stmt->fetch()) {
    echo 'exists';
} else {
    $sql = 'INSERT INTO `coupon code` (coupon, discount_percent,coupon_expiry) VALUES (?, ?,?)';
    $stmt = DatabaseHelper::runQuery($conn, $sql, [$coupon, $discountPercent, $expiry]);
    echo 'inserted';
}
