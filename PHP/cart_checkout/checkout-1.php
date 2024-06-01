<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE["user_id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- header and footer's css -->
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* @media (min-width: 768px) {
                body{
                    width: 750px;
                }
            }

            @media (min-width: 992px) {
                body{
                    width: 970px;
                }
            }

            @media (min-width: 1200px) {
                body{
                    width: 1200px;
                }
            } */
        /* .container0{
                background: #888;
                padding: 20px;
            } */
        body h1 {
            padding-left: 5%;
        }

        .container1 {
            display: block;
            flex-wrap: wrap;
            /* Optional: Allow sections to wrap if needed on small screens */
            justify-content: space-between;
            /* Horizontally space sections */
            padding: 20px;
        }

        .container0 hr {
            border: 1px solid #000;
            width: 100%;
            /* Line extends to 50% of the container width */
            margin: 1rem 0;
            /* 1rem margin above and below the line */
        }


        .shipping_address {
            display: flex;
            flex-direction: row;
            align-items: center;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: max-content;
            padding-inline: 3%;
            margin-left: 2%;
            margin-bottom: 15px;
        }

        .shipping_address span {
            margin-inline: 2%;
            font-size: 24px;
        }


        .shipping_address p {
            margin-right: 1%;
            font-size: 14px;
        }

        .shipping_address p:first-of-type {
            font-weight: bold;
            font-size: 16px;
        }


        .shipping_address a {
            background-color: green;
            padding-inline: 3%;
            padding-block: 2%;
            color: white;
            text-decoration: none;
            /* padding: 5px; */
            border-radius: 20px;
            font-size: 14px;
        }

        .order_summary {
            margin-left: 2%;
            width: 48%;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding-inline: 3%;
            padding-block: 2%;
        }

        /* .place_order {} */

        .payment {
            margin-left: 5%;
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }

        .payment h2 span {
            color: black;
        }

        .payment h2 {
            color: green;
        }

        .payment .cod {
            margin-bottom: 1%;
            margin-left: 2%;
        }

        .payment .card {
            margin-left: 2%;
            margin-bottom: 1%;
        }

        .payment .card_options {
            margin-left: 2%;
        }

        .payment input[type="radio"]input[type="radio"]:checked+label {
            margin-right: 0.5rem;
            color: green;
            font-weight: bold;
            /* Spacing between radio button and label */
        }

        @media only screen and (max-width: 768px) {

            .payment label,
            .payment input[type="radio"] {
                font-size: 0.8rem;
                /* Reduce font size for smaller screens */
            }
        }

        .payment label {
            display: inline-block;
            cursor: pointer;
            /* font-weight: bold; */
            margin-bottom: 1%;
        }

        .payment input[type="radio"]:checked+label {
            color: green;
            font-weight: bold;
        }

        .card_options {
            display: none;
            align-items: center;
            flex-direction: row;

        }



        .card_options span {
            margin-left: 10%;
            color: #7CE200;
        }

        .card_options a {
            border: 1px solid #7CE200;
            border-radius: 20px;
            padding: 10px;
            font-size: 15px;
            color: #000;
            font-weight: 400;
            background: #7CE200;
        }

        .card_options a:hover {
            background: #eee;
            color: #000;
            cursor: pointer;
        }

        .place_order {
            display: flex;
            justify-content: center;
        }

        .place_order button {
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 15px;
            color: white;
            font-weight: 400;
            background: green;
        }

        .place_order button:hover {
            cursor: pointer;
            background: white;
            color: black;
            border: green solid 2px;
        }

        input[id^="cvv_"] {
            display: none;
            margin-left: 2%;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 5px 10px;
            width: 10%;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* Could be more or less, depending on screen size */
        }

        #responseModal>.modal-content {
            width: fit-content;
            padding: 20px 50px;
            box-sizing: border-box;
            border-radius: 30px;
        }
    </style>
    <?php include_once '../header.php';
        ?>
</head>

<body>
    <?php include_once('../responseModal.inc.php'); ?>
    <div class="container0">
        <h1>Checkout</h1>
        <div class="container1">
            <div class="shipping_address">
                <!-- <span class="material-symbols-outlined shipping_address_icon">local_shipping</span> -->
                <span class="material-symbols-outlined">
                    local_shipping
                </span>
                <p>Shipping to:</p>
                <p><?php
                    $sql = "SELECT street_address, city FROM user WHERE id = :user_id";
                    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $user['street_address'] . ', ' . $user['city'];
                    ?></p>
                <a href="../profile/profile.php?section=address">Change</a>
            </div>
            <div class="order_summary">
                <?php
                $coupon = $_GET['coupon'];
                // echo $coupon;
                // echo '<input type="hidden" id="getcouponcheckout">';
                echo '<input type="hidden" id="coupon_status">';
                echo '<input type="hidden" id="coupon_input">';
                ?>
                <div class="total">
                    <?php include_once('update_cart_summary.php'); ?>

                </div>
            </div>
        </div>
        <hr>
        <form action="checkout.php" method="post" id="checkout_form">

            <input type="hidden" name="total" id="total" value="">
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('total').value = document.getElementById('placeorder_total').value;
                });
            </script>
            <div class="payment">
                <h2><span>Payment</span> Method:</h2>
                <div class="cod">
                    <label for="cod_radio">
                        <input type="radio" id="cod_radio" name="payment_method" value="cod">Cash on Delivery</label>
                </div>
                <div class="card">
                    <label for="card_radio">
                        <input type="radio" id="card_radio" name="payment_method" value="card">Credit Card</label>
                    <div class="card_options">
                        <?php

                        $sql = "SELECT `number`, `expiry` FROM `credit card` WHERE user_id = :user_id";
                        $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($results) == 0) {
                            echo '<div class="no-card"><span class="material-symbols-outlined">credit_card_off</span>';
                            echo '<p>You have no saved cards</p>';
                            echo '<p>Use COD or <a href="../profile/profile.php?section=cards" class="add_card"><i class="bx bx-plus"></i>add card</a></p></div>';
                        } else {
                            foreach ($results as $row) {
                                $lastFourDigits = substr($row['number'], -4);
                                $expiryDate = date('m/Y', strtotime($row['expiry']));
                                echo '<div class="card_option">';
                                echo '<input type="radio" id="card_' . $lastFourDigits . '" name="selected_card" value="' . $row['number'] . '">';
                                echo '<label for="card_' . $lastFourDigits . '">';
                                echo 'Card ending with ' . $lastFourDigits . ' (' . $expiryDate . ')';
                                echo '</label>';
                                echo '</div>';
                                echo '<input type="text" id="cvv_' . $lastFourDigits . '" name="cvv_' . $lastFourDigits . '" placeholder="CVV" required style="display: none;">';
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
            <div class="place_order">
                <button type="submit" name="place_order">Place Order</button>
            </div>
        </form>
    </div>


    <?php include_once('../footer.php'); ?>
    <script>
        $(document).ready(function() {
            $('#checkout_form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'place_order.php',
                    type: 'post',
                    data: $(this).serialize(),
                    dataType: 'json', // Expect a JSON response
                    success: function(response) {
                        // Handle the response from the server
                        if (response.output) {
                            showResponseModal(response.output);
                        }
                        if (response.order_nb) {
                            // Redirect to the order page
                            window.location.href = '../profile/order.php?order_nb=' + response.order_nb;
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle any errors
                        console.error(textStatus, errorThrown);
                    }
                });
            });
        });
        // used to show card options when card radio is selected and hide when cod radio is selected
        document.getElementById('card_radio').addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.card_options').style.display = 'flex';
                // When card options are shown, make CVV fields required
                document.querySelectorAll('input[id^="cvv_"]').forEach(function(element) {
                    element.required = true;
                });
            }
        });

        document.getElementById('cod_radio').addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.card_options').style.display = 'none';
                // When card options are hidden, make CVV fields not required
                document.querySelectorAll('input[id^="cvv_"]').forEach(function(element) {
                    element.required = false;
                });
            }
        });

        // used to show cvv field of selected card and hide others
        document.querySelectorAll('.card_option input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Hide all CVV input fields and make them not required
                document.querySelectorAll('input[id^="cvv_"]').forEach(function(element) {
                    element.style.display = 'none';
                    element.required = false;
                });

                // Show the CVV input field related to the selected card and make it required
                var lastFourDigits = this.id.split('_')[1];
                var cvvInput = document.getElementById('cvv_' + lastFourDigits);
                cvvInput.style.display = 'block';
                cvvInput.required = true;
            });
        });
    </script>

    <!-- to coupon validity using ajax -->
    <script src="../../JS/checkout_check_coupon.js"></script>
    <script>
        $(document).ready(function() {
            $('#coupon_input').val('<?php echo $coupon; ?>').trigger('change');
        });
    </script>
</body>

</html>