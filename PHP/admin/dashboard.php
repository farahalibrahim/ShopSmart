<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
?>
<div class="dashboard">
    <section id="tickets-section">
        <h2>Tickets</h2>
        <details>
            <summary>View Closed Tickets</summary>
            <section id="closed-tickets-section">
                <!-- closed tickets dynamically loaded -->
            </section>
        </details>
        <section id="open-tickets-section">
            <!-- open tickets dynamically loaded -->
        </section>
    </section>


    <section id="chat-section">
        <h2 id="chat-header">Ticket #</h2>

        <div class="chat_content" style="overflow-y: scroll;">
            <!-- Chat messages will go here -->
        </div>

        <div class="chat_footer">
            <input type="text" id="chat_input" placeholder="Type a message...">
            <button id="send_button">Send</button>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        // Fetch the tickets immediately
        fetchOpenTickets();
        fetchClosedTickets();

        // Then fetch the tickets every 20 seconds
        setInterval(fetchOpenTickets, 20000);
        setInterval(fetchClosedTickets, 20000);
    });

    function fetchOpenTickets() {
        // Send an AJAX request to fetch_tickets.php
        $.ajax({
            url: 'fetch_open_tickets.php',
            success: function(data) {
                // Replace the content of #closed-tickets-section with the fetched data
                $('#open-tickets-section').html(data);
            }
        });
    }

    function fetchClosedTickets() {
        // Send an AJAX request to fetch_tickets.php
        $.ajax({
            url: 'fetch_closed_tickets.php',
            success: function(data) {
                // Replace the content of #closed-tickets-section with the fetched data
                $('#closed-tickets-section').html(data);
            }
        });
    }
    var currentTicketId; // Declare a variable to store the current ticket ID

    $(document).on('click', '.open-chat', function() {
        currentTicketId = $(this).data('ticket-id'); // Store the ticket ID when you open the chat

        // Clear the chat content
        $('.chat_content').empty();

        // Update the chat header
        $('#chat-header').text('Ticket #' + currentTicketId);

        // Add the 'open' class to #chat-section to expand it
        $('#chat-section').addClass('open');

        // Load the chat messages immediately
        loadChatMessages();

        // Then load the chat messages every 20 seconds
        setInterval(loadChatMessages, 2000);
    });

    function loadChatMessages() {
        // Send an AJAX request to get the chat messages
        $.ajax({
            url: 'live_chat/load_messages.php',
            method: 'POST', // Use POST method
            data: {
                ticket_id: currentTicketId
            },
            success: function(data) {
                // empty chat content before adding 
                $('.chat_content').empty();
                // Append the chat messages to the chat content
                $('.chat_content').append(data);
            }
        });
    }

    $('#send_button').on('click', function() {
        var message = $('#chat_input').val();

        // Send an AJAX request to send the chat message
        $.ajax({
            url: 'live_chat/send_message.php',
            method: 'POST',
            data: {
                message: message,
                ticket_id: currentTicketId // Use the current ticket ID
            },
            success: function(data) {
                if (data === 'message_sent') {
                    // Clear the input
                    $('#chat_input').val('');

                    // Reload the chat messages
                    $('.open-chat[data-ticket-id="' + currentTicketId + '"]').click();
                } else {
                    alert('Message not sent');
                }
            }
        });
    });

    $(document).on('click', '.close-ticket', function() {
        var ticketId = $(this).data('ticket-id');

        // Send an AJAX request to close the ticket
        $.ajax({
            url: 'live_chat/close_ticket.php',
            method: 'POST',
            data: {
                ticket_id: ticketId
            },
            success: function(data) {
                alert('Ticket closed');
            }
        });
    });
</script>