<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$barcode = $_POST['barcode'];
$supermarket_id = $_POST['supermarket_id'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    $sql = "SELECT offer, offer_percent, offer_expiry FROM product WHERE barcode = :barcode AND supermarket_id = :supermarket_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode, 'supermarket_id' => $supermarket_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if offer is equal to 0
    if ($result['offer'] == 0) {
        echo "No offer";
    } else {
        echo json_encode($result);
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
