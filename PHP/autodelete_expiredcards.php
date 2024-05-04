<?php
include_once('../PHP/connection.inc.php');
include_once('../PHP/dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE["user_id"];

// Current date
$currentDate = date('Y-m-d');

// SQL query
$query = "DELETE FROM cards WHERE expiry < :currentDate && user_id = :user_id;";

// Prepare and execute the statement
$stmt = DatabaseHelper::runQuery($conn, $sql, ['currentDate' => $currentDate]);
