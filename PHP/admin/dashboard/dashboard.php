<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
?>
<div class="dashboard">
    <section id="tickets-section">
        <h2>Tickets</h2>
        <div id="ticket-search-bar">
            <select id="ticket-search-type">
                <option value="ticket_id">Ticket ID</option>
                <option value="user_id">User ID</option>
            </select>
            <input type="text" id="ticket-search-input" placeholder="Search...">
            <button id="ticket-clear-search"><span class='material-symbols-outlined'>close</span></button>
        </div>
        <details id="closed-tickets-details">
            <summary>View Closed Tickets</summary>
            <section id="closed-tickets-section">
                <!-- closed tickets dynamically loaded -->
            </section>
        </details>
        <section id="open-tickets-section">
            <!-- open tickets dynamically loaded -->
        </section>
        <div id="no-match" style="display: none;">
            <span class="material-symbols-outlined" style="font-size: 48px;">warning</span>
            <p>No matches found!</p>
        </div>
    </section>

    <section id="chat-section">
        <div id="chat-header" style="display: none;">Ticket #</div>

        <div class="chat_content" style="overflow-y: scroll; display:none;">
            <!-- Chat messages will go here -->
        </div>

        <div class="chat_footer" style="display: none;">
            <input type="text" id="chat_input" placeholder="Type a message...">
            <button id="send_button">Send</button>
        </div>
    </section>
</div>
<script>
    var userHasSearched = false;
    $('#ticket-clear-search').on('click', function() {
        $('#ticket-search-input').val('');
    });
    // search bar
    $(document).ready(function() {

        $('#ticket-search-type').on('change', function() {
            if ($('#ticket-search-input').val() !== '') {
                $('#ticket-search-input').keyup();
            } else {
                userHasSearched = false;
            }
        });
        $('#ticket-search-input').on('keyup', function() {
            userHasSearched = true;
            var value = $(this).val().toLowerCase();
            var type = $('#ticket-search-type').val();
            var closedTicketsMatch = false;
            var matchesFound = 0;
            $('table tr').each(function(index) {
                if (index !== 0) { // Exclude the first row (header row)
                    var id = $(this).find('td:first').text().toLowerCase();
                    var userId = $(this).find('td:nth-child(2)').text().toLowerCase();
                    if ((type === 'ticket_id' && id.indexOf(value) > -1) || (type === 'user_id' && userId.indexOf(value) > -1)) {
                        $(this).show();
                        matchesFound++;
                        if (value !== '' && (id === value || userId === value)) {
                            $(this).css('background-color', 'rgba(144, 238, 144, 0.5)'); //light opaque green
                        } else {
                            $(this).css('background-color', '');
                        }
                        if ($(this).parents('#closed-tickets-section').length) {
                            closedTicketsMatch = true;
                        }
                    } else {
                        // Only hide the row if it's not the header row
                        if ($(this).find('th').length === 0) {
                            $(this).hide();
                        }
                    }
                }
            });
            if (closedTicketsMatch && userHasSearched && $(this).val() !== '') {
                $('details').attr('open', true);
            } else if (userHasSearched && $(this).val() !== '') {
                $('details').removeAttr('open');
            }
            if (matchesFound === 0 && value !== '') {
                $('#open-tickets-section, #closed-tickets-details').hide();
                $('#no-match').show();
            } else {
                $('#open-tickets-section, #closed-tickets-details').show();
                $('#no-match').hide();
            }
        });
    });
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
            url: 'dashboard/fetch_open_tickets.php',
            success: function(data) {
                // Replace the content of #closed-tickets-section with the fetched data
                $('#open-tickets-section').html(data);
                // Reapply the search filter
                $('#ticket-search-input').keyup();
            }
        });
    }

    function fetchClosedTickets() {
        // Send an AJAX request to fetch_tickets.php
        $.ajax({
            url: 'dashboard/fetch_closed_tickets.php',
            success: function(data) {
                // Replace the content of #closed-tickets-section with the fetched data
                $('#closed-tickets-section').html(data);
                // Reapply the search filter
                if (userHasSearched) {
                    $('#ticket-search-input').keyup();
                }
            }
        });
    }
    var currentTicketId = ''; // Declare a variable to store the current ticket ID

    $(document).on('click', '.open-chat, #chat-section', function(event) {
        event.stopPropagation(); // Prevent the click event from bubbling up to the parent element

        // Check if the clicked element or any of its parents have a data-ticket-id attribute
        var ticketId = $(this).closest('[data-ticket-id]').data('ticket-id');
        currentTicketId = ticketId || currentTicketId; // If ticketId is undefined, keep the currentTicketId

        console.log(currentTicketId);
        // Clear the chat content and show since initially hidden
        $('.chat_content').empty().show();

        if (currentTicketId != '') {
            // If currentTicketId is not empty, keep the header as is => opened by click of section first time
            // $('#chat-header').text('Ticket #' + currentTicketId).show();
            $.ajax({
                url: 'dashboard/get_user_info.php', // The PHP script that will query the database
                type: 'POST',
                data: {
                    ticket_id: currentTicketId
                }, // Send the current ticket ID to the PHP script
                success: function(response) {
                    // Check if the response is already a JavaScript object
                    var userInfo;
                    if (typeof response === 'object') {
                        userInfo = response;
                    } else {
                        userInfo = JSON.parse(response);
                    }

                    // Create the div containing the ticket number, user name, and email
                    var infoDiv = $('<div>').html('<h2 style="margin-bottom:0;">Ticket #' + currentTicketId + '</h2><p style=" margin-top: 10px; margin-bottom: 0;">' + userInfo.name + '</p><p style="font-size: smaller; color: gray; margin-top: 5px;">' + userInfo.email + '</p>'); // Append the div to the chat header
                    $('#chat-header').html(infoDiv).show();
                }
            });
        } else {
            // If currentTicketId is empty, set the header to "Live Chat"
            $('#chat-header').html('<h2>Live Chat</h2>').show();
        }

        // Check if the clicked .open-chat button is within the #closed-tickets-section element
        if ($(this).closest('#closed-tickets-section').length > 0) {
            // If it is, hide the .chat_footer element
            $('.chat_footer').hide();
        } else {
            // Otherwise, show the .chat_footer element
            $('.chat_footer').show();
        }

        // Add the 'open' class to #chat-section to expand it
        $('#chat-section').addClass('open');

        loadChatMessages();

        // Clear the previous interval
        if (chatInterval) {
            clearInterval(chatInterval);
        }

        // Then load the chat messages every 20 seconds
        chatInterval = setInterval(loadChatMessages, 2000);

    });
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#chat-section').length) {
            // The click was outside the chat section
            if ($('#chat-section').hasClass('open')) {
                // The chat section is currently open
                $('#chat-header').hide();
                $('.chat_content').hide();
                $('.chat_footer').hide();
                $('#chat-section').removeClass('open');
            }
        }
    });
    // $(document).on('click', '#closed-tickets-section .open-chat', function() {
    //     currentTicketId = $(this).data('ticket-id'); // Store the ticket ID when you open the chat

    //     // Clear the chat content
    //     $('.chat_content').empty();

    //     // Update the chat header
    //     $('#chat-header').text('Ticket #' + currentTicketId);

    //     // Add the 'open' class to #chat-section to expand it
    //     $('#chat-section').addClass('open');

    //     // Hide the chat footer
    //     $('.chat_footer').hide();

    //     // Load the chat messages immediately
    //     loadChatMessages();

    //     // Then load the chat messages every 20 seconds
    //     setInterval(loadChatMessages, 2000);
    // });

    function loadChatMessages() {
        // Send an AJAX request to get the chat messages
        $.ajax({
            url: 'dashboard/live_chat/load_messages.php',
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
            url: 'dashboard/live_chat/send_message.php',
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

        // Define the callback function
        var callback = function() {
            // Send an AJAX request to close the ticket
            $.ajax({
                url: 'dashboard/live_chat/close_ticket.php',
                method: 'POST',
                data: {
                    ticket_id: ticketId
                },
                success: function(data) {
                    showResponseModal('Ticket closed Successfully');
                }
            });
        };

        // Show the confirmation modal
        showConfirmationModal('Are you sure you want to close this ticket?', callback);
    });
</script>