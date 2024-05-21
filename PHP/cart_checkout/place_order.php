<?php
ob_start(); // Start output buffering
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE["user_id"];
$output = null; // Initialize $output to null

$total = $_POST['total'];
// echo $total;
$payment_method = $_POST['payment_method'];
$cvv = null; // Initialize $cvv
if ($payment_method == 'card') {
    $card_number = $_POST['selected_card'];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'cvv_') === 0) {
            $cvv = $value;
            break;
        }
    }
    $sql = "SELECT * FROM `credit card` WHERE `number` = :card_number";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['card_number' => $card_number]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() > 0) {
        if ((string)$cvv !== (string)$results[0]['cvv']) {
            $output = "CVV does not match. Please try again.";
            echo json_encode(['output' => $output]);
            exit; //stop executing the rest of script
        }
        if ($total > $results[0]['balance']) {
            $output = "Your card's balance not enough, try another card.";
            echo json_encode(['output' => $output]);
            exit; //stop executing the rest of script
        }
    }
}

$sql = "INSERT INTO `order` (user_id, order_date, status, order_total, payment_method) 
        VALUES (:user_id, :order_date, :status, :order_total, :payment_method)";
$currentDate = date('Y-m-d'); // date the order is made
$stmt = DatabaseHelper::runQuery($conn, $sql, [
    'user_id' => $user_id,
    'order_date' => $currentDate,
    'status' => 'processing',
    'order_total' => $total,
    'payment_method' => $payment_method
]);

if ($stmt) {
    $order_nb = $conn->lastInsertId(); // order nb
    $sql = "SELECT c.*, p.price FROM cart c JOIN product p ON c.product_barcode = p.barcode && c.supermarket_id = p.supermarket_id WHERE c.user_id = :user_id";
    $cartItems = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cartItems as $item) {
        $sql = "INSERT INTO order_details (order_nb, product_barcode, supermarket_id, quantity, price) 
                VALUES (:order_nb, :barcode, :supermarket, :quantity, :price)";
        $stmt = DatabaseHelper::runQuery($conn, $sql, [
            'order_nb' => $order_nb,
            'barcode' => $item['product_barcode'],
            'supermarket' => $item['supermarket_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }

    $sql = "SELECT street_address, city, phone FROM user WHERE id = :user_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    $street_address = $userInfo['street_address'];
    $city = $userInfo['city'];
    $phone = $userInfo['phone'];

    $sql = "INSERT INTO shipment(order_nb, payment_method, status, street_address,city,user_phone,payment_status,payment_card)
            VALUES (:order_nb, :payment_method, :status, :street, :city, :phone, :payment_status, :card)";
    $stmt = DatabaseHelper::runQuery($conn, $sql, [
        'order_nb' => $order_nb,
        'payment_method' => $payment_method,
        'status' => "pending",
        'street' => $userInfo['street_address'],
        'city' => $userInfo['city'],
        'phone' => $userInfo['phone'],
        'payment_status' => "pending",
        'card' => ($payment_method == 'card') ? $card_number : null
    ]);

    $sql = "DELETE FROM cart WHERE user_id = :user_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
    $output = ob_get_clean(); // Get the output and clean the buffer
    echo json_encode(['output' => $output, 'order_nb' => $order_nb]);
} else {
    $output = ob_get_clean(); // Get the output and clean the buffer
    echo json_encode(['output' => $output, 'error' => 'An error occurred']);
}
