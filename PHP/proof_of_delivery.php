<?php
include_once('../PHP/connection.inc.php');
include_once('../PHP/dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['fileToUpload'])) {
        $order_nb = $_POST['order_nb'];
        $target_dir = "../uploadedFiles/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $newfilename = $order_nb . "." . $imageFileType;
        $target_file = $target_dir . $newfilename;

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $sql = "UPDATE `shipment` SET status = 'delivered' WHERE order_nb = :order_nb;
                    UPDATE `order` SET status = 'delivered' WHERE order_nb = :order_nb;";
            DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order_nb]);

            $stmt = DatabaseHelper::runQuery($conn, "SELECT * FROM `shipment` WHERE order_nb = :order_nb", ['order_nb' => $order_nb]);
            $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($shipment['payment_method'] == 'cod') {
                $sql = "UPDATE `shipment` SET payment_status = 'paid' WHERE order_nb = :order_nb";
                DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order_nb]);
            } elseif ($shipment['payment_method'] == 'card') {

                $payment_card = $_POST['payment_card'];
                $sql = "SELECT `payment_card`, `order_total` FROM `shipment` JOIN `order` ON shipment.order_nb = `order`.order_nb WHERE shipment.order_nb = :order_nb";
                $stmt = DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order_nb]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $payment_card = $result['payment_card'];
                $order_total = $result['order_total'];

                $sql = "UPDATE `user` SET balance = balance - :order_total WHERE card_number = :payment_card";
                $stmt = DatabaseHelper::runQuery($conn, $sql, ['order_total' => $order_total, 'payment_card' => $payment_card]);
            }

            exit("The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.");
        } else {
            exit("Sorry, there was an error uploading your file.");
        }
    }
}
