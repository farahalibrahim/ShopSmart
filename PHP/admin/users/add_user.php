<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$pass = $_POST['pass'];
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
$street = $_POST['street'];
$city = $_POST['city'];
$role = $_POST['role'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    // Check if email or phone already exists
    $sql = "SELECT email, phone FROM user WHERE email = :email OR phone = :phone";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email, 'phone' => $phone]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        if ($existingUser['email'] == $email) {
            echo "Email already exists.";
        }
        if ($existingUser['phone'] == $phone) {
            echo "Phone already exists.";
        }
        exit;
    }

    // Insert new user
    $sql = "INSERT INTO user (name, email, phone, password, street_address, city, role) VALUES (:name, :email, :phone, :pass, :street, :city, :role)";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['name' => $name, 'email' => $email, 'phone' => $phone, 'pass' => $hashed_pass, 'street' => $street, 'city' => $city, 'role' => $role]);

    echo "User added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
