<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
}
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
}
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if (isset($_COOKIE['user_id'])) : ?>
        <title><?php $nameParts = explode(' ', trim($_COOKIE["user_name"]));
                echo $nameParts[0]; ?>'s Cart</title>
    <?php else : ?>
        <title>Login Please</title>
    <?php endif; ?>

    <?php if (isset($_COOKIE['user_id'])) : ?>
        <title><?php $nameParts = explode(' ', trim($_COOKIE["user_name"]));
                echo $nameParts[0]; ?>'s Cart</title>
    <?php else : ?>
        <title>Login Please</title>
    <?php endif; ?>

    <!-- header and footer's css -->
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <?php include_once '../accountFreezeModal.inc.php' ?>
    <?php include_once '../accountFreezeModal.inc.php' ?>

    <style>
        /* .related {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px;
        } */
        .Container-for-all {
            margin: 30px 60px 30px 30px;
            padding: 50px 5% 0 5%;

            background: #fbfbf9;





        }

        .not-logged-in {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            /* Optional: Change this to the height you want */
        }

        .Container-for-all hr {}

        h1 {

            text-align: start;
            /* Center align the text */
            padding-top: 40px;
            color: green;
        }


        .empty_cart {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            /* Responsiveness styles */
            max-width: 500px;
            margin: 0 auto;
        }

        .empty_cart a {
            border-radius: 15px;
            color: black;
            background: green;
            padding: 10px;
        }

        .related {
            display: flex;
            align-items: center;

        }

        .relatedcard {
            background-color: #fff;
            /* Set a white background */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add a slight shadow */
            border-radius: 5px;
            /* Add rounded corners */
            padding: 15px;
            /* Add some padding */
            margin: 10px;
            /* Add some margin between cards */
            display: flex;
            /* Use flexbox for positioning */
            flex-direction: column;
            /* Stack elements vertically */
            justify-content: center;
            /* Center content vertically */
            align-items: center;
            /* Center content horizontally */
            text-align: center;
            /* Center text within the card */
            max-width: 200px;
            /* Set a maximum width for responsiveness */
            width: 120px;
            height: 120px;
        }


        .relatedcard img {
            width: 80%;
        }

        .relatedcard .card-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .relatedcard .card-info h3 {
            display: flex;
            align-items: center;
            white-space: nowrap;
            /* Restrict text wrapping to one line */
            overflow: hidden;
            /* Hide content that overflows */
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .related_container hr {

            border-bottom: 3px solid #000;
            /* Bottom border */
            padding: 10px 20px;
            /* Optional padding for spacing */
            margin: 0;
            /* Remove default margin */
            text-align: center;
            /* Center align the text */
        }

        .related_container {
            width: 100%;

        }

        .card-info h3 {
            margin: 0;

        }

        .card-info p {

            display: flex;
            align-items: center;
        }

        .card-info a {
            width: 25px;
            /* Set a width for the arrow */
            height: 25px;
            /* Set a height for the arrow */
            fill: currentColor;
            /* Inherit the card's text color */
            color: green;
            border: 1px solid;
            border-radius: 40px;
            color: #eee;
            background: green;
        }

        .card-info a:hover {
            color: black;
            background: #eee;
        }

        #catarrow {
            font-size: 20px;
        }

        .supermarket_card {
            display: flex;
            flex-wrap: nowrap;
            /* Prevent line breaks */
            overflow-x: auto;
            /* Enable horizontal scroll */
            height: 330px;
            width: 100%;
            /* Force full width within supermarket section */

        }

        .productcard {
            background-color: #fff;
            /* White background */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Subtle shadow for depth */
            border-radius: 20px;
            /* Rounded corners */
            margin: 10px;
            /* Spacing between cards */
            display: flex;
            /* Arrange content in rows */
            flex-direction: column;
            /* Stack elements vertically */
            align-items: center;
            width: 300px;
            /* Adjust width as needed */
            text-align: center;
            /* Center text within the card */
            height: 270px;
        }

        .productcard .productdetails {
            color: #333;
            /* Text color */
            margin-bottom: 5px;
            /* Space between description and price */
            max-width: 250px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;

        }

        .productcard .productdetails h3 {
            font-size: 18px;
            /* Title font size */
            font-weight: bold;
            /* Bold title text */
            margin-bottom: 0;
            /* Space between title and description */
            white-space: nowrap;
            max-width: 250px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .productcard .productdetails p {
            font-size: 13px;
            /* Price font size */
            color: #000;
            /* Orange color for price */
            margin-bottom: 0;
            margin-top: 3px;

        }

        .productcard .productdetails button {
            width: 30px;
            height: 30px;
            font-size: 15px;
            background: green;

            border-radius: 20px;
            color: #eee;


        }

        .productcard .productdetails .weight {
            padding-left: 20px;
            align-items: flex;
            margin: 20px;
            font-weight: 20%;
        }

        .productcard .productdetails input {
            width: 20%;
            height: auto;
            font-size: 15px;
        }

        .product_price {
            font-weight: bold;
            font-size: 16px !important;
            margin-top: 10px !important;
        }

        .product_actions {
            display: flex;
            flex-direction: row;
            align-items: baseline;
            justify-content: flex-start;
            margin-top: 5px;
        }

        .productimg {
            width: 110px;
            /* Image spans the full card width */
            height: 100px;
            /* Adjust height as needed */
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
            /* Smooth hover effect */
            /* margin-left: 30%; */
        }

        .productimg:hover {
            transform: scale(1.05);
            /* Enlarge image slightly on hover */
            opacity: 0.9;
            /* Reduce opacity slightly on hover */
        }

        .productcard .pro_cont {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .payment_icons {
            display: flex;
            align-items: center;
        }

        .payment_methods {
            margin-bottom: 20px;


        }

        .payment_methods i {
            font-size: 50px;
        }

        .payment_methods img {
            width: 50px;
            height: 50px;
        }

        .checkout_btn {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            width: 100%;
        }

        .cart_summary {
            width: 300px;
            /* Adjust width as needed */
            border: 1px solid #ddd;
            /* Add a thin border */
            padding: 15px;
            /* Add some padding */
            margin: 0 auto;
            /* Center the receipt horizontally */
            font-family: Arial, sans-serif;
            /* Set a font family */

        }

        .cart_summary h2 {
            margin-bottom: 10px;
        }

        .cart_summary .coupon {
            margin-top: 5px;
            font-size: 15px;
            font-weight: bold;
        }

        .cart_summary .coupon button {
            border-radius: 20px;

            color: #eee;

            background: green;
            padding: 5px;
            font-weight: 100;

        }

        .cart_summary .total {
            margin-top: 5px;
        }

        .down-container {
            display: grid;
            align-items: center;
            justify-content: space-between;
            width: 500px;

        }

        .supermarket_container {
            display: grid;
            grid-template-columns: 1fr;
            /* Single column for supermarkets */
            grid-gap: 1rem;
            /* Gap between supermarkets */
        }

        .Container-for-all .related_container h2 span {
            color: green;
        }

        .productcard .other_option a {
            color: gray;
            display: flex;
            justify-content: space-between;
        }

        .productcard .other_option a:hover {
            color: green;
        }

        .cart-head {
            display: flex;
            align-items: baseline;
            border-top: 3px solid #ddd;
            /* Top border */
            border-bottom: 3px solid #000;
            /* Bottom border */
            padding: 0px 20px;
            /* Optional padding for spacing */
            margin: 0;
            /* Remove default margin */
        }

        .cart-head p {
            padding-top: 70px;
            padding-left: 10px;
        }
    </style>


    <!-- stop event bubbling for redirect a tag -->
    <script>
        // numberInputs = document.querySelectorAll('.productcard input[type="number"]');

        // numberInputs.forEach((input) => {
        //     input.addEventListener('click', (event) => {
        //         event.stopImmediatePropagation();
        //     });
        // });
    </script>

    <!-- import AJAX jquery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="Container-for-all">

        <!-- header -->
        <?php include_once '../header.php';
        ?>

        <?php
        if (isset($_COOKIE['user_id'])) :
            $sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = :user_id";
            $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['count'] == 0) {
                echo "<div class='empty_cart'>";
                echo "<span class='material-symbols-outlined empty_cart_icon'>shopping_cart_off </span>";
                echo "<h1>Your cart is empty</h1>";
                echo "<a href='http://localhost:3000/PHP/main/index.php#products' class='shop_btn'>Go shopping</a>";
                exit();
            } else {
                echo "<div class='cart-head'>";
                echo "<h1>Your Cart</h1>";
                echo "<p id='items_count'>(" . $result['count'] . " items)</p>";
                echo "</div>"
        ?>
                <div class="related_container">
                <?php
                echo "<h2> <span>Related</span> Items</h2>";
                // Related items section
                echo "<div class='related'>";


                $sql = "SELECT DISTINCT product.tag , cart.product_barcode
                        FROM cart 
                        INNER JOIN product ON cart.product_barcode = product.barcode 
                        WHERE cart.user_id = :user_id";

                $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);



                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as $result) {


                    $sql = "SELECT barcode, product_name, product_image, MIN(price) as min_price, MAX(price) as max_price 
                    FROM product 
                    WHERE tag = :tag AND barcode != :barcode 
                    LIMIT 2;";

                    $itemStmt = DatabaseHelper::runQuery($conn, $sql, ['tag' => $result['tag'], 'barcode' => $result['product_barcode']]);
                    $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);;

                    foreach ($items as $item) {
                        if (!empty($item['barcode'])) { // Check if the barcode is not empty

                            // process product image
                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                            $mimeType = $finfo->buffer($item['product_image']);

                            $imageData = base64_encode($item['product_image']);
                            $src = 'data:' . $mimeType . ';base64,' . $imageData;

                            echo '<div class="relatedcard">';
                            echo '<img src="' . $src . ' " alt="' . $item['product_name'] . ' ">';
                            echo ' <div class="card-info">';
                            // echo '<h3>' . $item['product_name'] . '</h3>';
                            // echo '<p>$' . $item['min_price'] . ' - $' . $item['max_price'] . '</p>';
                            echo '<a href="viewproduct.php?barcode=' . $item['barcode'] . '"><span class="material-symbols-outlined" id="catarrow">arrow_forward</span></a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            }
                ?>
                </div>
                <hr>
                <h2><span id="cartprod">Cart</span> Products</h2>
                <div class="supermarket_container">

                    <?php


                    // cart products display section
                    $sql = "SELECT DISTINCT cart.*, supermarket.name 
            FROM cart 
            INNER JOIN supermarket ON cart.supermarket_id = supermarket.id
            WHERE cart.user_id = :user_id
            GROUP BY cart.supermarket_id;";


                    $stmt = DatabaseHelper::runQuery($conn, $sql, ["user_id" => $user_id]);

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $supermarketName = $row['name'];
                        $supermarketId = $row['supermarket_id'];

                        // Create a div for each distinct supermarket
                        echo "<h2>$supermarketName</h2>";
                        echo "<div  class='supermarket_card'>";


                        // Get all items from this supermarket and display them in the table format
                        $itemsSql = "SELECT product.*, product.quantity AS product_quantity, cart.quantity FROM cart 
                    INNER JOIN product ON cart.product_barcode = product.barcode AND cart.supermarket_id = product.supermarket_id
                    WHERE cart.user_id = :user_id AND cart.supermarket_id = :supermarket_id
                    ORDER BY product.supermarket_id;";

                        $products_stmt = DatabaseHelper::runQuery($conn, $itemsSql, ["user_id" => $user_id, "supermarket_id" => $supermarketId]);

                        while ($product = $products_stmt->fetch(PDO::FETCH_ASSOC)) {
                            $productName = $product['product_name'];
                            $productPrice = $product['price'];
                            $productQuantity = $product['quantity'];

                            //process product image after retrieve
                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                            $mimeType = $finfo->buffer($product['product_image']);

                            $imageData = base64_encode($product['product_image']);
                            $src = 'data:' . $mimeType . ';base64,' . $imageData;

                            // Create a card for each product
                            echo "<div class='productcard'>";
                            // echo "<a href='viewproduct.php?barcode=" . $product['barcode'] . "'><div class='productcard'>";
                            echo '<img class="productimg" src="' . $src . '" alt="' . $product['product_name'] . '">'; // use $src here
                            echo "<div class='productdetails'><h3>$productName</h3>";

                            $quantity = $product['product_quantity'];
                            $quantity_type = $product['quantity_type'];
                            $unit = '';

                            if ($quantity >= 1000) {
                                $quantity /= 1000;
                                $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
                            } else {
                                $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
                            }
                            echo "<p>{$quantity} {$unit}</p>";
                            echo "<script>console.log(" . $quantity . ");</script>";

                            echo "<p class='product_price'> $$productPrice</p>";
                            echo "<div class='product_actions'><input type='number' value='$productQuantity' data-barcode='" . $product['barcode'] . "' data-supermarket-id='$supermarketId' class='quantity-input'>";
                            echo "<button class='delete-button' data-barcode='" . $product['barcode'] . "' data-supermarket-id='$supermarketId'><i class='bx bx-trash-alt'></i></button></div>";
                            // echo "</div></div></a>"; 
                            echo "</div>";
                            // Check for other options with lower price
                            $otherOptionsSql = "SELECT * FROM product WHERE barcode = :barcode AND price < :price ORDER BY price ASC LIMIT 1";
                            $otherOptionsStmt = DatabaseHelper::runQuery($conn, $otherOptionsSql, ["barcode" => $product['barcode'], "price" => $productPrice]);
                            $otherOption = $otherOptionsStmt->fetch(PDO::FETCH_ASSOC);

                            if ($otherOption) {
                                echo "<div class='other_option'>";
                                // echo "<a href='viewproduct.php?barcode=" . $otherOption['barcode'] . "'>Other option from " . $otherOption['supermarket_name'] . " at $" . $otherOption['price'] . "</a>";
                                echo "<a href='../products/viewproduct.php?barcode=" . $otherOption['barcode'] . "'>Other option at $" . $otherOption['price'] . "<span class='material-symbols-outlined'>arrow_forward</span></a>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }

                        echo "</div>";
                    }
                    echo "</div>";
                    ?>
                </div>


                <br>
                <hr><br>
                <div class="down-container">
                    <!-- accepted payment methods display section -->
                    <div class="payment_methods">
                        <h3>We accept</h3>
                        <div class="payment_icons">
                            <i class='bx bxl-mastercard'></i>
                            <i class='bx bxl-visa'></i>
                            <img src="../../pics/cod.png" alt="cash on delivery">
                        </div>
                    </div>

                    <!-- cart summary section -->
                    <div class="cart_summary">
                        <h2>Cart Summary</h2>
                        <div class="coupon">
                            <input type="text" name="coupon" id="coupon_input" placeholder="Coupon Code">
                            <button id="apply_coupon">Apply</button>
                            <p id="coupon_status" style="display:none;">Coupon invalid!</p>
                        </div>
                        <input type="hidden" id="user_id" value="<?php echo $user_id ?>"><!-- to be used in ajax request for user_id retrieval -->
                        <!-- <input type="hidden" id="discount"">//to be used in ajax request for user_id retrieval -->
                        <div class="total">
                            <?php include_once('update_cart_summary.php'); ?>
                        </div>
                    </div>
                </div>


                <br>
                <button class="checkout_btn">Checkout</button>
            <?php
        else :
            ?>
                <div class="not-logged-in">
                    <span class="material-symbols-outlined" style="font-size: 40px;">person_off </span>
                    <h3>Login or Signup to check your cart</h3>
                    <a href="http://localhost:3000/PHP/login.php" style="color: green; font-weight: bold;">Login/Signup</a>
                </div>
            <?php
        endif; ?>
    </div>
    <!-- to update item quantities dynamically using ajax -->
    <script src="../../JS/updatecart.js">
    </script>

    <script>
        // redirect to checkout page when checkout button is clicked
        window.onload = function() {
            var btn = document.querySelector('.checkout_btn');
            btn.addEventListener('click', function() {
                var couponStatus = document.getElementById('coupon_status').innerText;
                var userId = getCookie('user_id');

                // Retrieve the account status from the database
                $.ajax({
                    url: '../get_account_status.php',
                    method: 'POST',
                    data: {
                        user_id: userId
                    },
                    success: function(data) {
                        if (data === 'freezed') {
                            showFreezeModal(userId, "OOPS! Unfortunately, you can't place an order at this time.");
                        } else {
                            if (couponStatus !== "Coupon Invalid!") { // valid coupon
                                var couponInput = document.getElementById('coupon_input').value;
                                window.location.href = 'checkout.php?coupon=' + couponInput;
                            } else { //invalid coupon
                                window.location.href = 'checkout.php';
                            }
                        }
                    }
                });
            });
        }

        function getCookie(name) {
            // cookies are stored as cookiename = cookievalue; ... so we need to split by ; and then find our name value pair
            // nameEQ is the name we are looking for, name of the cookie + '=' to match the structure cookies are stored in
            var nameEQ = name + "=";
            // due to the structure of cookies, we need to split by ; to find our name value pair
            var ca = document.cookie.split(';');
            // loop through all the cookies until we find a match for our cookie name
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                // remove leading whitespace, check if first char is space if yes form string after it
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                // if the cookie name is found return the value of the cookie
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
    </script>
    <!-- to remove items from cart using ajax -->
    <script src="../../JS/delete_from_cart.js"></script>
    <!-- to coupon validity using ajax -->
    <script src="../../JS/check_coupon.js"></script>
    <!-- <script>
        window.onload = function() {
            var inputs = document.getElementsByClassName('quantity-input');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].addEventListener('click', function(event) {
                    event.stopPropagation();
                });
                inputs[i].addEventListener('focus', function(event) {
                    event.stopPropagation();
                });
                inputs[i].addEventListener('change', function(event) {
                    event.stopPropagation();
                });
                inputs[i].addEventListener('mousedown', function(event) {
                    event.preventDefault();
                });
            }
        }
    </script> -->

    <!-- footer -->
    <?php include_once '../footer.php'; ?>
    </div>

</body>

</html>