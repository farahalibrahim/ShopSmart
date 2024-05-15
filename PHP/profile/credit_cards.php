<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user_id = $_COOKIE['user_id'];

$query = "SELECT * FROM `credit card` WHERE user_id = :user_id";
$stmt = DatabaseHelper::runQuery($conn, $query, ['user_id' => $user_id]);
$credit_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<button id="addCardButton"><span class="material-symbols-outlined">add</span>Add Card</button>';
if (count($credit_cards) > 0) {
    echo '<div class="header"><h2>Your Credit Cards</h2></div>';
    foreach ($credit_cards as $credit_card) {
        echo '<div class="card">';
        echo '<span class="material-symbols-outlined card_icon">credit_card</span>';
        echo '<h3>Card ending in ' . substr($credit_card['number'], -4) . '</h3>';
        echo '<p>' . $credit_card['name_on_card'] . '</p>';
        echo '<p>' . $credit_card['expiry'] . '</p>';
        echo "<button class='delete_button' data-card='" . $credit_card['number'] . "' data-user='" . $user_id . "'><i class='bx bx-trash' ></i></button>";
        echo '</div>';
    }
} else {
    echo '<div class="no-card"> <span class="material-symbols-outlined">credit_card_off</span>';
    echo '<h2>No Credit Cards Found</h2>';
    echo '<p>Use the button above to add</p></div>';
}

// // Print order details as array key and value
// foreach ($credit_cards as $credit_card) {
//     foreach ($credit_card as $key => $value) {
//         if ($key !== 'product_image') {
//             echo '<p>' . $key . ': ' . $value . '</p>';
//         }
//     }
//     echo "<br>  ";
// }
?>
<!-- delete card modal -->
<div id="responseModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="responseText"></p>
    </div>
</div>
<!-- add card modal -->
<div id="addCardModal" style="display: none;">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <form id="addCardForm">
            <label for="cardNumber">Card Number</label>
            <input type="text" id="cardNumber" placeholder="Card Number" required><br>
            <label for="cardHolderName">Card Holder Name</label>
            <input type="text" id="cardHolderName" placeholder="Card Holder Name" required><br>
            <label for="expiryDate">Expiry Date</label>
            <input type="date" id="expiryDate" required><br>
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" placeholder="CVV" required><br>
            <label for="balance">Balance</label>
            <input type="text" id="balance" placeholder="Balance" required><br>
            <button type="submit">Save</button>
        </form>
    </div>
    <script>
        // Get the delete modal
        var modal = document.getElementById("responseModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        $(document).on('click', '.delete_button', function() {
            var card = $(this).data('card');
            var user = $(this).data('user');

            $.ajax({
                url: 'delete_card.php',
                type: 'POST',
                data: {
                    card: card,
                    user: user
                },
                success: function(response) {
                    // Display the response in a modal
                    $('#responseText').text(response);
                    modal.style.display = "block";
                },
                complete: function() {
                    // Manually trigger click event on #credit_cards element
                    $('#credit_cards').click();
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
        // Add card button click event
        $('#addCardButton').click(function() {
            $('#addCardModal').show();
        });


        // When the user clicks on <span> (x), close the modal
        $('#closeModalButton').click(function() {
            $('#addCardModal').hide();
        });

        // When the user clicks anywhere outside of the modal, close it
        $(window).click(function(event) {
            if (event.target == document.getElementById('addCardModal')) {
                $('#addCardModal').hide();
            }
        });

        // Add card form submit event
        $('#addCardForm').submit(function(event) {
            event.preventDefault();

            var cardNumber = $('#cardNumber').val();
            var cardHolderName = $('#cardHolderName').val();
            var expiryDate = $('#expiryDate').val();
            var cvv = $('#cvv').val();
            var balance = $('#balance').val();

            $.post('add_card.php', {
                user_id: <?php echo $user_id; ?>,
                card_number: cardNumber,
                card_holder_name: cardHolderName,
                expiry_date: expiryDate,
                cvv: cvv,
                balance: balance
            }, function(data) {
                if (data.success) {
                    alert('Card added successfully');
                    $('#addCardModal').hide(); // Hide the modal
                    // Manually trigger click event on #credit_cards element
                    $('#credit_cards').click();
                } else {
                    alert('An error occurred: ' + data.message);
                }
            }, 'json');
        });
    </script>