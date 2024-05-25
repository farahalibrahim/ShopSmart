<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$orderNb = $_POST['order_nb'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    $sql = "UPDATE `order` SET `status` = 'cancelled' WHERE order_nb = :order_nb";

    $stmt = DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $orderNb]);

    echo "Order cancelled successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
