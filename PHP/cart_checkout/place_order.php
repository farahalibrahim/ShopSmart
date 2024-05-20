<?php
echo "redirected";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
include_once '../responseModal.inc.php'; //for alert message
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE["user_id"];
echo $user_id;

$total = $_POST['total'];
echo $total;
$payment_method = $_POST['payment_method'];
echo $payment_method;
if ($payment_method == 'card') {
    $card_number = $_POST['selected_card'];
    echo $card_number;
    // get name of cvv field
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'cvv_') === 0) {
            // This is a CVV input field
            $cvv = $value;
            echo $cvv;
            break;
        }

        if (strpos($key, 'expiry_') === 0) {
            // This is a expiry field
            $expiry = $value;
            echo $expiry;
            break;
        }
    }
    $sql = "SELECT * FROM `credit card` WHERE `number` = :card_number";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['card_number' => $card_number]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // print_r($results);
    if ($stmt->rowCount() > 0) {
        // $credit_card = $results[0];

        if ($cvv != $results[0]['cvv']) {
            // CVV or total does not match, handle the error
            // CVV or total does not match, handle the error
            echo "<script>showResponseModal('CVV does not match. Please try again.');</script>";
            echo "<script>window.location.href = 'checkout.php';</script>";
        }
        if ($total > $results[0]['balance']) {
        }
    }
} else if ($payment_method == 'cod') {
    // $card_number = null;
    // $expiry_date = null;
    // $cvv = null;
}
// shipment: order_nb	payment_method	status	street_address	city	user_phone	payment_status	payment_card
// order: order_nb	user_id	supermarket_id	order_date	status	order_total	payment_method	
// order_details: order_nb	product_barcode	supermarket_id	quantity	price	
