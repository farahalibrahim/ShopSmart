<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';


$userId = $_POST['userId'];
$status = $_POST['status'];
// $freeze_reason = $_POST['freezeReason'];
if (isset($_POST['freezeReason'])) {
    $freeze_reason = $_POST['freezeReason'];
} else {
    $freeze_reason = NULL;
}

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    if ($status == 'active') {
        $freeze_expiry = NULL;
        // $freeze_reason = NULL;
    } else {
        $freeze_expiry = date('Y-m-d', strtotime('+2 days'));
    }
    $stmt = DatabaseHelper::runQuery($conn, "UPDATE user SET account_status = :status, freeze_expiry = :freeze_expiry, freeze_reason = :freeze_reason WHERE id = :userId", ['status' => $status, 'userId' => $userId, 'freeze_expiry' => $freeze_expiry, 'freeze_reason' => $freeze_reason]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        echo 'Updated';
    } else {
        echo 'No rows were updated';
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
