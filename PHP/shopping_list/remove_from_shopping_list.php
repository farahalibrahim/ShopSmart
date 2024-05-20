<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE['user_id'];
$barcode = $_POST['barcode'];
$supermarket_id = $_POST['supermarket_id'];

$sql = "DELETE FROM `shopping_list` WHERE user_id = :user_id AND product_barcode = :barcode AND supermarket_id = :supermarket_id";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id, 'barcode' => $barcode, 'supermarket_id' => $supermarket_id]);
if ($stmt->rowCount() > 0) {
    echo "Product removed from shopping list";
} else {
    echo "Failed to remove product from shopping list";
}
