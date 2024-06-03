<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$website = $_POST['website'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    // Check if email or phone already exists
    $sql = "SELECT email, phone FROM supermarket WHERE email = :email OR phone = :phone";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email, 'phone' => $phone]);
    $existingSupermarket = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingSupermarket) {
        if ($existingSupermarket['email'] == $email) {
            echo "Email already exists.";
        }
        if ($existingSupermarket['phone'] == $phone) {
            echo "Phone already exists.";
        }
        exit;
    }

    // Insert new supermarket
    $sql = "INSERT INTO supermarket (name, email, phone, website) VALUES (:name, :email, :phone, :website)";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['name' => $name, 'email' => $email, 'phone' => $phone, 'website' => $website]);

    echo "Supermarket added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
