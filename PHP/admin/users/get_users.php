<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    $stmt = $conn->prepare("SELECT * FROM user");
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
