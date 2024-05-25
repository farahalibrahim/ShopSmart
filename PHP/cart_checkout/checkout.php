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
        body h1{
            padding-left: 45%;
            color: gray;
        }
        .container1 {
            display: block;
            flex-wrap: wrap; /* Optional: Allow sections to wrap if needed on small screens */
            justify-content: space-between; /* Horizontally space sections */
            padding: 20px;
        }
        .container0 hr{
            border: 1px solid #000;
    width: 100%;  /* Line extends to 50% of the container width */
    margin: 1rem 0;  /* 1rem margin above and below the line */
        }
        

        .shipping_address {
            margin-left: 3%;
            width: 48%; /* Adjust width as needed */
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background: #7CE200;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }
        .shipping_address span{
            margin-left: 5%;
            font-size: 50px;
            font-weight: bold;
            
        }
        .shipping_address p{
            margin-left: 5%;
            
        }
        .shipping_address a{
            margin-top: 5%;
            margin-left: 40%;
            color: white;
            border-radius: 10px;
            background: black;
            padding: 10px;
            
        }
        .order_summary{
            margin-left: 3%;
            width: 48%; /* Adjust width as needed */
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #f5f5f5;

        }
        .place_order{
            
        }
        .payment {
            margin-left: 20%;
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }
        .payment h2 span {
            color: #888;
        }
        .payment h2 {
            color: #7CE200;
        }
        .payment .cod{
            margin-bottom: 10px;
            margin-left: 5%;
        }
        .payment .card {
            
            margin-left: 5%;
        }
        .payment input[type="radio"] {
        margin-right: 0.5rem; /* Spacing between radio button and label */
        }
        @media only screen and (max-width: 768px) {
        .payment label,
        .payment input[type="radio"] {
            font-size: 0.8rem;  /* Reduce font size for smaller screens */
        }
}

        .payment label {
        display: inline-block; /* Makes text next to radio button */
        cursor: pointer; /* Indicates clickable element on hover */
        font-weight: bold;
        margin-bottom: 0.5rem;
        }

        .payment input[type="radio"]:checked + label {
        color: #337ab7; /* Change text color to primary color on selection */
        }

        .card_options {
            display: none;
        }
        .card_options span{
            margin-left: 10%;
            color: #7CE200;
        }
        .card_options a{
            border: 1px solid #7CE200;
            border-radius: 20px;
            padding: 10px;
            font-size: 15px;
            color: #000;
            font-weight: 400;
            background: #7CE200;
        }
        .card_options a:hover{
            background: #eee;
            color: #000;
            cursor: pointer;
        }
        .place_order button{
            margin-left: 25%;
            border: 1px solid #7CE200;
            border-radius: 20px;
            padding: 10px;
            font-size: 15px;
            color: #000;
            font-weight: 400;
            background: #7CE200;
        }
        .place_order button:hover{
            cursor: pointer;
        }

        input[id^="cvv_"] {
            display: none;
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
    <?php
    // if (isset($_POST["place_order"])) {
    //     $total = $_POST['total'];
    //     echo $total;
    //     $payment_method = $_POST['payment_method'];
    //     $cvv = null; // Initialize $cvv
    //     // echo $payment_method;
    //     if ($payment_method == 'card') {
    //         $card_number = $_POST['selected_card'];
    //         // echo $card_number;
    //         // get name of cvv field
    //         foreach ($_POST as $key => $value) {
    //             if (strpos($key, 'cvv_') === 0) {
    //                 // This is a CVV input field
    //                 $cvv = $value;
    //                 // echo $cvv;
    //                 break;
    //             }
    //         }
    //         $sql = "SELECT * FROM `credit card` WHERE `number` = :card_number";
    //         $stmt = DatabaseHelper::runQuery($conn, $sql, ['card_number' => $card_number]);
    //         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //         print_r($results);
    //         if ($stmt->rowCount() > 0) {
    //             // $credit_card = $results[0];

    //             if ((string)$cvv !== (string)$results[0]['cvv']) {
    //                 // CVV or total does not match, handle the error
    //                 // CVV or total does not match, handle the error
    //                 echo "<script>alert('CVV does not match. Please try again.');</script>";
    //                 exit; //stop executing the rest of script
    //             }
    //             if ($total > $results[0]['balance']) {
    //                 echo "<script>alert('Your card's balance not enough, try another card.');</script>";
    //                 exit; //stop executing the rest of script
    //             }
    //         }
    //     }
    //     // create an order
    //     $sql = "INSERT INTO `order` (user_id, order_date, status, order_total, payment_method) 
    //         VALUES (:user_id, :order_date, :status, :order_total, :payment_method)";
    //     $currentDate = date('Y-m-d'); // date the order is made
    //     $stmt = DatabaseHelper::runQuery($conn, $sql, [
    //         'user_id' => $user_id,
    //         'order_date' => $currentDate,
    //         'status' => 'processing',
    //         'order_total' => $total,
    //         'payment_method' => $payment_method
    //     ]);

    //     if ($stmt) {
    //         $order_nb = $conn->lastInsertId(); // order nb

    //         // get user cart and price of products in cart
    //         $sql = "SELECT c.*, p.price FROM cart c JOIN product p ON c.product_barcode = p.barcode && c.supermarket_id = p.supermarket_id WHERE c.user_id = :user_id";
    //         $cartItems = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    //         // print_r($cartItems);
    //         // exit;

    //         foreach ($cartItems as $item) {
    //             // move cart items to order
    //             $sql = "INSERT INTO order_details (order_nb, product_barcode, supermarket_id, quantity, price) 
    //                 VALUES (:order_nb, :barcode, :supermarket, :quantity, :price)";
    //             $stmt = DatabaseHelper::runQuery($conn, $sql, [
    //                 'order_nb' => $order_nb,
    //                 'barcode' => $item['product_barcode'],
    //                 'supermarket' => $item['supermarket_id'],
    //                 'quantity' => $item['quantity'],
    //                 'price' => $item['price']
    //             ]);
    //         }

    //         // shipment details of order
    //         // user address and phone, to be used in shipment
    //         $sql = "SELECT street_address, city, phone FROM user WHERE id = :user_id";
    //         $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
    //         $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    //         $street_address = $userInfo['street_address'];
    //         $city = $userInfo['city'];
    //         $phone = $userInfo['phone'];

    //         // shipment
    //         $sql = "INSERT INTO shipment(order_nb, payment_method, status, street_address,city,user_phone,payment_status,payment_card)
    //             VALUES (:order_nb, :payment_method, :status, :street, :city, :phone, :payment_status, :card)";
    //         $stmt = DatabaseHelper::runQuery($conn, $sql, [
    //             'order_nb' => $order_nb,
    //             'payment_method' => $payment_method,
    //             'status' => "pending",
    //             'street' => $userInfo['street_address'],
    //             'city' => $userInfo['city'],
    //             'phone' => $userInfo['phone'],
    //             'payment_status' => "pending",
    //             'card' => ($payment_method == 'card') ? $card_number : null
    //         ]);

    //         // delete user cart after it becomes an order
    //         $sql = "DELETE FROM cart WHERE user_id = :user_id";
    //         $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
    //         header('Location: ../profile/order.php?order_nb=' . $order_nb); // redirect to order page
    //         // header('Location: ../HTML/test.php'); // redirect to order page
    //     } else {
    //         // Handle the error
    //     }
    // }

    ?>



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
                document.querySelector('.card_options').style.display = 'block';
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