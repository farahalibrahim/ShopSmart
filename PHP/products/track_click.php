<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$sql = 'UPDATE product SET clicks = clicks + 1 WHERE barcode = :barcode';
$stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $_POST['barcode']]);
