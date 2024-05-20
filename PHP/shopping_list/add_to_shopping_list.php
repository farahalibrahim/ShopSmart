<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE['user_id'];
$barcode = $_POST['barcode'];
$supermarket_id = $_POST['supermarket_id'];

$sql = "SELECT price FROM product WHERE barcode = :barcode AND supermarket_id = :supermarket_id";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode, 'supermarket_id' => $supermarket_id]);

$price = $stmt->fetchColumn();


$sql = "INSERT INTO `shopping_list` VALUES (:user_id,:barcode, :supermarket_id, :price)";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id, 'barcode' => $barcode, 'supermarket_id' => $supermarket_id, 'price' => $price]);
if ($stmt->rowCount() > 0) {
    echo "Product added to shopping list";
} else {
    echo "Failed to add product to shopping list";
}
