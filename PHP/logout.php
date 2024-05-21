<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user = $_COOKIE['user_id'];

$sql = "UPDATE user SET login_status = 'logged_out' WHERE id = :user";

$stmt = DatabaseHelper::runQuery($conn, $sql, ['user' => $user]);

setcookie('user_id', "", time() - 3600, '/');
setcookie('user_name', "", time() - 3600, '/');
