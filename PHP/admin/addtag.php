<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if (isset($_GET['add_tag'])) {
    $tag = $_GET['tag'];
    $tag_name = $_GET['tag_name'];
    $sql = "INSERT INTO `product_tags` (`tag`, `tag_title`) VALUES ('$tag', '$tag_name');";
    $stmt = DatabaseHelper::runQuery($conn, $sql);
    header("Location: admin.php?add_tag=success");
}
