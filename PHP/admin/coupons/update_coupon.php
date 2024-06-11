<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$coupon = $_POST['coupon'];
$discountPercent = $_POST['discount_percent'];
$couponExpiry = $_POST['coupon_expiry'];

$sql = "UPDATE `coupon code` SET discount_percent = :discountPercent, coupon_expiry = :couponExpiry WHERE coupon = :coupon";
$attr = ['discountPercent' => $discountPercent, 'couponExpiry' => $couponExpiry, 'coupon' => $coupon];
$stmt = DatabaseHelper::runQuery($conn, $sql, $attr);

echo "Coupon updated successfully";
