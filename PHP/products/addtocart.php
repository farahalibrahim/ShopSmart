<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$barcode = $_POST['barcode'];
$supermarket_id = $_POST['supermarket_id'];
$user_id = $_COOKIE['user_id'];

// SQL query to check if an entry exists
$sql = "SELECT * FROM cart WHERE user_id = :user_id AND supermarket_id = :supermarket_id AND product_barcode = :barcode";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id, 'supermarket_id' => $supermarket_id, 'barcode' => $barcode]);

if ($stmt->rowCount() > 0) {
    // If an entry exists, increment the quantity
    $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = :user_id AND supermarket_id = :supermarket_id AND product_barcode = :barcode";
} else {
    // If no entry exists, insert a new one
    $sql = "INSERT INTO cart (user_id, supermarket_id, product_barcode, quantity) VALUES (:user_id, :supermarket_id, :barcode, 1)";
}

$stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id, 'supermarket_id' => $supermarket_id, 'barcode' => $barcode]);

// Check for SQL errors
if (!$stmt) {
    $error = $stmt->errorInfo();
    echo "SQL Error: " . $error[2];
}
