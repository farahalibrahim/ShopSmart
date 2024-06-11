<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);


$searchType = $_POST['searchType'];
$searchValue = $_POST['searchValue'];
if ($searchValue == '') {
    $sql = "SELECT * FROM `coupon code`";
    $stmt = DatabaseHelper::runQuery($conn, $sql);
} else {
    if ($searchType == 'coupon') {
        $sql = "SELECT * FROM `coupon code` WHERE $searchType LIKE :searchValue";
        $attr = ['searchValue' => '%' . $searchValue . '%'];
    } else {
        $sql = "SELECT * FROM `coupon code` WHERE $searchType = :searchValue";
        $attr = ['searchValue' => $searchValue];
    }
    $stmt = DatabaseHelper::runQuery($conn, $sql, $attr);
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='coupon_card'>";
    echo "<div class='coupon_icon'><span class='material-symbols-outlined'>confirmation_number</span></div>";
    echo "<div class='coupon_info'>";
    echo "<h3 class='couponCode'>" . $row['coupon'] . "</h3>";
    echo "<h5 class='discount_percent'>" . $row['discount_percent'] . "%</h5>";
    echo "<p>Expiry: " . $row['coupon_expiry'] . "</p>";
    echo "</div>";
    echo "<div class='coupon_actions'>";
    echo "<button class='edit_coupon' data-coupon='" . $row['coupon'] . "'><span class='material-symbols-outlined'>edit</span></button>";
    echo "<button class='delete_coupon' data-coupon='" . $row['coupon'] . "'><span class='material-symbols-outlined'>delete</span></button>";
    echo "</div>";
    echo "</div>";
}
