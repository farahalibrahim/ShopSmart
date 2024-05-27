<?php
include_once 'connection.inc.php';
include_once 'dbh.class.inc.php';

$user = $_POST['user_id'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    $sql = 'SELECT account_status FROM user WHERE id =  "' . $user . '"';
    $stmt = DatabaseHelper::runQuery($conn, $sql);
    $freeze = $stmt->fetchColumn();

    if ($freeze == 'freeze') {
        echo "freezed";
    } else {
        echo "active";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
