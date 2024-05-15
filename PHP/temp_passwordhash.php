<?php
// include_once('connection.inc.php');
// include_once('dbh.class.inc.php');
// $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// // Fetch all users
// $sql = "SELECT id, password FROM user";
// $stmt = DatabaseHelper::runQuery($conn, $sql);
// $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // Loop through each user
// foreach ($users as $user) {
//     // Hash the password
//     $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

//     // Update the user's password in the database
//     $sql = "UPDATE user SET password = :password WHERE id = :id";
//     $stmt = DatabaseHelper::runQuery($conn, $sql, ['password' => $hashed_password, 'id' => $user['id']]);
// }

// echo "Passwords updated successfully.";

// include_once('connection.inc.php');
// include_once('dbh.class.inc.php');
// $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Fetch all products
// $sql = "SELECT barcode FROM product";
// $stmt = DatabaseHelper::runQuery($conn, $sql);
// $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // Loop through each product
// foreach ($products as $product) {
//     // Generate a random date in the format 'YYYY-MM-DD'
//     $randomDate = date("Y-m-d", mt_rand(strtotime('2024-06-31'), strtotime('2026-12-31')));

//     // Update the product's expiry_date in the database
//     $sql = "UPDATE product SET expiry_date = :expiry_date WHERE barcode = :barcode";
//     $stmt = DatabaseHelper::runQuery($conn, $sql, ['expiry_date' => $randomDate, 'barcode' => $product['barcode']]);
// }

// echo "Expiry dates updated successfully.";

// echo "Passwords updated successfully.";

include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Fetch all products
$sql = "SELECT `number` FROM `credit card`";
$stmt = DatabaseHelper::runQuery($conn, $sql);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through each product
foreach ($cards as $card) {
    // Generate a random date in the format 'YYYY-MM-DD'
    $randomDate = date("Y-m-d", mt_rand(strtotime('2024-06-31'), strtotime('2026-12-31')));

    // Update the product's expiry_date in the database
    $sql = "UPDATE `credit card` SET expiry = :expiry WHERE `number` = :card";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['expiry' => $randomDate, 'card' => $card['number']]);
}

echo "Expiry dates updated successfully.";
