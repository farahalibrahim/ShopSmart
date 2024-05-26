<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$barcode = $_POST['offer_barcode'];
$supermarket_id = $_POST['offer_supermarket'];
$offer_percent = $_POST['offer_percent'];
$offer_expiry = $_POST['offer_expiry'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    $sql = "SELECT price FROM product WHERE barcode = :barcode AND supermarket_id = :supermarket_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode, 'supermarket_id' => $supermarket_id]);
    $original_price = $stmt->fetchColumn();

    $new_price = $original_price - ($original_price * ($offer_percent / 100));

    $sql = "UPDATE product SET offer = 1, offer_percent = :offer_percent, offer_expiry = :offer_expiry, price = :price WHERE barcode = :barcode AND supermarket_id = :supermarket_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['offer_percent' => $offer_percent, 'offer_expiry' => $offer_expiry, 'price' => $new_price, 'barcode' => $barcode, 'supermarket_id' => $supermarket_id]);
    if ($stmt->rowCount() > 0) {
        echo 'success';
    } else {
        echo 'fail';
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
