<?php
// session_start(); // Start the session

include_once '../../../connection.inc.php';
include_once '../../../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the ticket ID from the POST data
if (isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];
} else {
    $ticket_id = '';
}
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
$user_id = $_COOKIE['user_id'];

if ($ticket_id != "") {
    // Select the messages for this ticket
    $sql = "SELECT * FROM messages WHERE ticket_id = :ticket_id ORDER BY timestamp ASC";
    $stmt = DatabaseHelper::runQuery($conn, $sql, [':ticket_id' => $ticket_id]);

    // Fetch the messages and output them
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alignment = ($row['sender_id'] == $user_id) ? 'right' : 'left';
        $color = ($row['sender_id'] == $user_id) ? 'lightgreen' : '#ededed';
        echo "<div style='text-align: $alignment;'><div style='display: inline-block; padding: 10px; border-radius: 10px; background-color: $color; margin: 7px;font-size: 0.9em;'>" . htmlspecialchars($row['content']) . "</div></div>";
    }
} else {
    echo "<div class='no_ticket' style='display: flex; justify-content: center; flex-direction: column; align-items: center; height: 100%;'>";
    echo "<span class='material-symbols-outlined'>add_task</span>";
    echo "<strong>Select a ticket to solve</strong></div>";
    // echo "<p>Start chatting with our support team!</p>";
}
