<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$id = $_POST['id'];
$name = $_POST['name'];
$website = $_POST['website'];
$email = $_POST['email'];
$phone = $_POST['phone'];
try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    $sql = "UPDATE supermarket SET name = ?, website = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = DatabaseHelper::runQuery($conn, $sql, [$name, $website, $email, $phone, $id]);

    if ($stmt->rowCount() > 0) {
        echo 'success';
    } else {
        echo 'no rows affected';
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
