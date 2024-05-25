<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$search = $_POST['search'];
$search_type = $_POST['search_type'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);


    if ($search_type == 'order_nb') {
        $stmt = DatabaseHelper::runQuery($conn, "SELECT user.* FROM user JOIN `order` ON user.id = `order`.user_id WHERE `order`.order_nb LIKE :search", ['search' => '%' . $search . '%']);
    } else {
        $stmt = DatabaseHelper::runQuery($conn, "SELECT * FROM user WHERE $search_type LIKE :search", ['search' => '%' . $search . '%']);
    }

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        // output user data as HTML
        echo "<div class='user-card'>";
        echo "<div class='user-icon'>
        <span class='material-symbols-outlined'>account_circle</span></div>";
        echo "<div class='user-info'>";
        echo "<h4 class='user-name'>" . $user['name'] . "</h4>";
        echo "<p class='user-email'>" . $user['email'] . "</p>";
        echo "<p class='user-phone'>" . $user['phone'] . "</p>";
        if ($user['account_status'] != 'active') {
            echo "<p class='user-status'>" . $user['account_status'] . "</p>";
        }
        echo "</div>";
        echo "<div class='user-actions'>";
        echo "<button class='orders-button' data-id='" . $user['id'] . "'><span class='material-symbols-outlined'>orders</span><span>Orders</span></button>";
        echo "<button class='edit-button' data-id='" . $user['id'] . "'><span class='material-symbols-outlined'>edit</span></button>";
        echo '<select class="user-select">
                <option value="active" ' . ($user['account_status'] == 'active' ? 'selected' : '') . '>Active</option>
                <option value="freeze" ' . ($user['account_status'] == 'freeze' ? 'selected' : '') . '>Freeze</option>
            </select>';
        echo "</div>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
