<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    $data = json_decode(file_get_contents('php://input'), true);
    $barcode = $data['barcode'];
    $supermarketId = $data['supermarket_id'];
    $user_id = $_COOKIE['user_id'];

    $sql = "DELETE FROM cart WHERE user_id = :user_id AND product_barcode = :barcode AND supermarket_id = :supermarket_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id, 'barcode' => $barcode, 'supermarket_id' => $supermarketId]);

    if (!$stmt) {
        $error = $stmt->errorInfo();
        echo json_encode(["error" => "SQL Error: " . $error[2]]);
    } else {
        echo json_encode(["success" => true]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
