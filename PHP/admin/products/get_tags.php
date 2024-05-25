<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    $sql = "SELECT tag, tag_title FROM product_tags";
    $stmt = DatabaseHelper::runQuery($conn, $sql);
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create select options
    foreach ($tags as $tag) {
        echo "<option value=\"" . $tag['tag'] . "\">" . $tag['tag'] . " - " . $tag['tag_title'] . "</option>";
    }
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
