<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orders = $_POST['orders'];

    foreach ($orders as $order_nb) {
        $sql = "UPDATE `shipment` SET status = 'out_for_delivery' WHERE order_nb = :order_nb";
        DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order_nb]);

        $sql = "UPDATE `order` SET status = 'out_for_delivery' WHERE order_nb = :order_nb";
        DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order_nb]);
    }

    header('Location: delivery.php');
    exit;
}
