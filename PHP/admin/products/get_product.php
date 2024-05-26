<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$barcode = $_POST['barcode'];
$supermarket_id = $_POST['supermarket_id'];

$sql = "SELECT * FROM product WHERE barcode = :barcode AND supermarket_id = :supermarket_id";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode, 'supermarket_id' => $supermarket_id]);

$product = $stmt->fetch();

if ($product) {
    // If the product was found, encode it as JSON
    echo json_encode($product);
} else {
    // If the product was not found, return an empty JSON object or some error message
    echo json_encode([]);
}
