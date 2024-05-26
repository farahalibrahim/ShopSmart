<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$barcode = $_POST['edit_barcode'];
$supermarket_id = $_POST['edit_supermarket'];
$product_name = $_POST['product_name'];
$manufacturer = $_POST['manufacturer'];
$quantity_type = $_POST['quantity_type'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$expiry_date = $_POST['expiry_date'];
$category = $_POST['category'];
$tag = $_POST['tag'];

$sql = "UPDATE product SET product_name = :product_name, manufacturer = :manufacturer, quantity_type = :quantity_type, quantity = :quantity, price = :price, expiry_date = :expiry_date, category = :category, tag = :tag WHERE barcode = :barcode AND supermarket_id = :supermarket_id";
$attr = [
    'product_name' => $product_name,
    'manufacturer' => $manufacturer,
    'quantity_type' => $quantity_type,
    'quantity' => $quantity,
    'price' => $price,
    'expiry_date' => $expiry_date,
    'category' => $category,
    'tag' => $tag,
    'barcode' => $barcode,
    'supermarket_id' => $supermarket_id
];
$stmt = DatabaseHelper::runQuery($conn, $sql, $attr);
$result = $stmt->rowCount();

if ($result > 0) {
    echo "success";
} else {
    echo "fail";
}
