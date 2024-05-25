<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    // Get the user ID from the POST data
    $userId = $_POST['userId'];

    $stmt = DatabaseHelper::runQuery($conn, "SELECT * FROM user WHERE id = :id", ['id' => $userId]);

    // Fetch the user details
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // JSON string
    echo json_encode($userDetails);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
