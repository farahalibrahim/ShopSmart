<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// open tickets
$sql = "SELECT tickets.id as ticket_id, tickets.user_id, tickets.timestamp, m1.content 
        FROM tickets 
        INNER JOIN (
            SELECT content, ticket_id
            FROM messages m2
            WHERE timestamp = (
                SELECT MIN(timestamp)
                FROM messages
                WHERE ticket_id = m2.ticket_id
            )
        ) m1 ON tickets.id = m1.ticket_id 
        WHERE tickets.status = 'open'
        ORDER BY tickets.timestamp ASC";

$stmt = DatabaseHelper::runQuery($conn, $sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    echo "<table>";
    echo "<tr><th>Ticket ID</th><th>User ID</th><th>Timestamp</th><th>Message</th></tr>";

    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ticket_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
        echo "<td>" . htmlspecialchars($row['content']) . "</td>";
        echo "<td><button class='close-ticket' data-ticket-id='" . htmlspecialchars($row['ticket_id']) . "'>
        <span class='material-symbols-outlined'>chat</span><span>Close</span></button></td>";
        echo "<td><button class='open-chat' data-ticket-id='" . htmlspecialchars($row['ticket_id']) . "'>
        <span class='material-symbols-outlined'>mark_chat_read</span><span>Chat</span></button></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo '<div id="no_open_ticket">
    <span class="material-symbols-outlined" style="font-size: 48px;">sentiment_satisfied</span>
    <p>No Open Tickets!</p>
</div>';
}
