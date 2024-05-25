<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$sql = "SELECT tag, tag_title FROM product_tags";
$stmt = DatabaseHelper::runQuery($conn, $sql);
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tags);
