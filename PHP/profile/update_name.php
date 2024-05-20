<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';

// Get the new name from the POST request
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

// Get the user id from the POST request
$user_id = $_POST['id'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE `user` SET `name` = :name WHERE id = :id";

    $stmt = DatabaseHelper::runQuery($conn, $sql, ['name' => $firstname . ' ' . $lastname, 'id' => $user_id]);

    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        echo "Name updated successfully";
    } else {
        echo "No rows updated. Check if the ID exists or the name was the same.";
    }
} catch (PDOException $e) {
    echo "Error updating name: " . $e->getMessage();
}

// Close the connection
$conn = null;
