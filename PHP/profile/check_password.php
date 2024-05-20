<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['oldPassword'])) {
        $userId = $_COOKIE['user_id'];
        $oldPassword = $_POST['oldPassword'];

        $sql = "SELECT password FROM `user` WHERE id = :user";
        $stmt = DatabaseHelper::runQuery($conn, $sql, ['user' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($oldPassword, $result['password'])) {
            echo 'match';
        } else {
            echo 'no match';
        }
    }
}
