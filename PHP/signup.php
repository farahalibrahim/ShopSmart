<?php
// signup.php
include_once 'connection.inc.php';
include_once 'dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Get POST data
$email = $_POST['email'];
$pass = $_POST['pass'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$street = validate($_POST['street']); //no regex for street and city
$city = validate($_POST['city']); // insure no special characters and html

// Prepare SQL query to check if email or phone already exists
$sql = "SELECT * FROM user WHERE email = :email OR phone = :phone";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email, 'phone' => $phone]);

if ($stmt->rowCount() > 0) {
    // Email or phone already exists
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['email'] == $email) {
        echo json_encode(['status' => 'error', 'message' => 'Email already used! Login or try another email']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Phone already used, try something else']);
    }
} else {
    // Email and phone do not exist, insert new user
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT); // Hash password
    $sql = "INSERT INTO user (email, `name`, `password`, phone, street_address, city, `role`) VALUES (:email, :pass, :name, :phone, :street, :city, 'user')";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email, 'pass' => $hashedPass, 'name' => $name, 'phone' => $phone, 'street' => $street, 'city' => $city]); // Insert new user

    // Get the last inserted ID
    $last_id = $conn->lastInsertId();

    // Set cookies
    setcookie('user_id', $last_id, time() + 86400, '/');
    setcookie('user_name', $name, time() + 86400, '/');

    // Return role to AJAX
    echo json_encode(['status' => 'success', 'role' => 'user']);
}
