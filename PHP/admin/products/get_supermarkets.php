<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    $sql = "SELECT id, `name` FROM supermarket";

    $stmt = DatabaseHelper::runQuery($conn, $sql);

    // Create select options
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
    }
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
