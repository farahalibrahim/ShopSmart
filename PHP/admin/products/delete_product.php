<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    $sql = "DELETE FROM product WHERE barcode = :barcode AND supermarket_id = :supermarket_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $_POST['barcode'], 'supermarket_id' => $_POST['supermarket_id']]);

    $affected_rows = $stmt->rowCount();

    if ($affected_rows > 0) {
        echo 'success';
    } else {
        echo 'fail';
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
