<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Fetch the closed tickets
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
        WHERE tickets.status = 'closed'
        ORDER BY tickets.timestamp ASC";

$stmt = DatabaseHelper::runQuery($conn, $sql);

// Generate the closed tickets table
echo "<table>";
echo "<tr><th>Ticket ID</th><th>User ID</th><th>Timestamp</th><th>Message</th></tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['ticket_id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
    echo "<td>" . htmlspecialchars($row['content']) . "</td>";
    echo "<td><button class='open-chat' data-ticket-id='" . htmlspecialchars($row['ticket_id']) . "'>
    <span class='material-symbols-outlined'>history</span><span>Chat History</span></button></td>";
    echo "</tr>";
}

echo "</table>";
