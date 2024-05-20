<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user_id = $_COOKIE['user_id'];

$query = "SELECT * FROM `credit card` WHERE user_id = :user_id";
$stmt = DatabaseHelper::runQuery($conn, $query, ['user_id' => $user_id]);
$credit_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<div class="header"><h2>Your Credit Cards</h2></div>';
echo '<div class="add_button"><button id="addCardButton"><span class="material-symbols-outlined">add</span>Add Card</button></div>';
if (count($credit_cards) > 0) {
    foreach ($credit_cards as $credit_card) {
        echo '<div class="card">';
        echo '<div class="card_details"><span class="material-symbols-outlined card_icon">credit_card</span>';
        echo '<h3>Card ending in ' . substr($credit_card['number'], -4) . '</h3>';
        echo '<p>' . $credit_card['name_on_card'] . '</p>';
        echo '<p>' . $credit_card['expiry'] . '</p></div>';
        echo "<button class='delete_button' data-card='" . $credit_card['number'] . "' data-user='" . $user_id . "'><span class='material-symbols-outlined'>delete</span></button>";
        echo '</div>';
    }
} else { //no cards div, to be styled in the center of document
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
<!-- <div id="responseModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="responseText"></p>
    </div>
</div> -->
<!-- for delete/add card response -->
<?php include '../responseModal.inc.php'; ?>

<!-- add card modal -->
<div id="addCardModal" style="display: none;">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <form id="addCardForm">
            <div class="inputDiv">
                <!-- <label for="cardNumber">Card Number</label> -->
                <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" required>
                <p id="cardNumberStatus" class="status" style="display: none;"></p><br>
            </div>
            <div class="inputDiv">
                <!-- <label for="cardHolderName">Card Holder Name</label> -->
                <input type="text" id="cardHolderName" placeholder="John Doe" required><br>
            </div>
            <div class="inputDiv">
                <!-- <label for="expiryDate">Expiry Date</label> -->
                <input type="month" id="expiryDate" placeholder="MM/YYYY" min="<?php echo date("Y-m"); ?>" required><br>
            </div>
            <p id="cardHolderNameStatus" class="status" style="display: none;"></p>
            <div class="inputDiv">
                <!-- <label for="cvv">CVV</label> -->
                <input type="text" id="cvv" placeholder="123" required>
            </div>
            <p id="cvvStatus" class="status" style="display: none;"></p>
            <div class="inputDiv">
                <!-- <label for="balance">Balance</label> -->
                <input type="text" id="balance" placeholder="1000" required>
            </div>
            <p id="balanceStatus" class="status" style="display: none;"></p>
            <button type="submit">Save</button>
        </form>
    </div>
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

    $(document).off('click', '.delete_button').on('click', '.delete_button', function() {
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
                // Display the response in a modal that fades after 5 sec
                showResponseModal(response, function() {
                    $('#credit_cards').click();
                });
            },

            error: function(xhr, status, error) {
                console.log(error);
                showResponseModal(error);
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
        console.log('Expiry Date:', expiryDate);
        var cvv = $('#cvv').val();
        var balance = $('#balance').val();

        var cardNumberRegex = /^[0-9]{16}$/;
        var cardHolderNameRegex = /^.{1,255}$/;
        var cvvRegex = /^[0-9]{3}$/;
        var balanceRegex = /^[0-9]+(\.[0-9]{1,2})?$/;

        var isValid = true;

        if (!cardNumberRegex.test(cardNumber)) {
            $('#cardNumberStatus').text('Invalid card number').show();
            isValid = false;
        } else {
            $('#cardNumberStatus').hide();
        }

        if (!cardHolderNameRegex.test(cardHolderName)) {
            $('#cardHolderNameStatus').text('Invalid card holder name').show();
            isValid = false;
        } else {
            $('#cardHolderNameStatus').hide();
        }

        if (!cvvRegex.test(cvv)) {
            $('#cvvStatus').text('Invalid CVV').show();
            isValid = false;
        } else {
            $('#cvvStatus').hide();
        }

        if (!balanceRegex.test(balance)) {
            $('#balanceStatus').text('Invalid balance').show();
            isValid = false;
        } else {
            $('#balanceStatus').hide();
        }

        if (!isValid) {
            return;
        }

        $.post('add_card.php', {
            user_id: <?php echo $user_id; ?>,
            card_number: cardNumber,
            card_holder_name: cardHolderName,
            expiry_date: expiryDate,
            cvv: cvv,
            balance: balance
        }, function(data) {
            if (data.success) {
                $('#addCardModal').hide(); // Hide the modal
                showResponseModal('Card added successfully', function() {
                    // Manually trigger click event on #credit_cards element
                    $('#credit_cards').click();
                });
            } else {
                $('#addCardModal').hide(); // Hide the modal
                showResponseModal('An error occurred: ' + data.message);
            }
        }, 'json');
    });
</script>