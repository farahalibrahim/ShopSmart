<?php
include_once('../PHP/connection.inc.php');
include_once('../PHP/dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE["user_id"];
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $nameParts = explode(' ', trim($_COOKIE["user_name"]));
            echo $nameParts[0]; ?>'s Cart</title>

    <!-- header and footer's css -->
    <link rel="stylesheet" href="../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />


    <style>
        /* .related {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px;
        } */

        .relatedcard {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0px 10px rgba(0, 0, 0, 0.2);
            margin: 10px;
            width: 10em;
            height: 10em;
        }

        .relatedcard img {
            width: 7em;
            height: 7em;
            object-fit: contain;
            margin-right: 10px;
        }

        .card-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .card-info h3 {
            margin: 0;
        }

        .card-info p {
            margin: 0;
        }

        .card-info a {
            text-decoration: none;
            color: black;
        }

        .card-info a:hover {
            color: blue;
        }

        #catarrow {
            font-size: 30px;
        }

        .supermarket_card {
            margin: 5px;
            margin: 20px;
            padding: 10px 20px;
            border-radius: 20px;
            box-shadow: 0 0px 10px rgba(0, 0, 0, 0.2);
        }

        .productcard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0px 10px rgba(0, 0, 0, 0.2);
        }

        .productimg {
            width: 200px;
            height: 200px;
            object-fit: contain;
            margin-right: 10px;
            /* box-shadow: 0 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 20px; */
        }

        .payment_icons {
            display: flex;
            align-items: center;
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
    <!-- header -->
    <?php include_once '../PHP/header.php';
    ?>
    <?php
    $sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = :user_id";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['count'] == 0) {
        echo "<div class='empty_cart'>";
        echo "<span class='material-symbols-outlined empty_cart_icon'>shopping_cart_off </span>";
        echo "<h1>Your cart is empty</h1>";
        echo "<a href='http://localhost:3000/HTML/test.php#categories' class='shop_btn'>Go shopping</a>";
        exit();
    } else {
        echo "<h1>Your Cart</h1>";
        echo "<p id='items_count'>(" . $result['count'] . " items)</p>";

        // Related items section
        echo "<div class='related'>";
        echo "<h2>Related Items</h2>";

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
                    echo '<h3>' . $item['product_name'] . '</h3>';
                    echo '<p>$' . $item['min_price'] . ' - $' . $item['max_price'] . '</p>';
                    echo '<a href="viewproduct.php?barcode=' . $item['barcode'] . '"><span class="material-symbols-outlined" id="catarrow">arrow_forward</span></a>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        }
    }


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
        echo "<div  class='supermarket_card'>";
        echo "<h2>$supermarketName</h2>";

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
            if ($product['quantity_type'] == 'weight') {
                if ($product['product_quantity'] >= 1000) {
                    echo '<p>' . $product['product_quantity'] / 1000 . ' kg </p>';
                } else if ($product['product_quantity'] < 1000) {
                    echo '<p>' . $product['product_quantity'] . ' g </p>';
                }
            } else if ($product['quantity_type'] == 'piece') {
                echo '<p>' . $product['product_quantity']  . ' pieces</p>';
            } else if ($product['quantity_type'] == 'liquid') {
                if ($product['product_quantity'] >= 1000) {
                    echo '<p>' . $product['product_quantity'] / 1000  . ' l</p>';
                } else if ($product['product_quantity'] < 1000) {
                    echo '<p>' . $product['product_quantity'] . ' ml </p>';
                }
            }
            echo "<p>Price: $$productPrice</p>";
            echo "<input type='number' value='$productQuantity' data-barcode='" . $product['barcode'] . "' data-supermarket-id='$supermarketId' class='quantity-input'>";
            echo "<button class='delete-button' data-barcode='" . $product['barcode'] . "' data-supermarket-id='$supermarketId'><i class='bx bx-trash-alt'></i></button>";
            // echo "</div></div></a>"; 
            echo "</div></div>";
        }

        echo "</div>";
    }
    ?>

    <br>
    <hr><br>

    <!-- accepted payment methods display section -->
    <div class="payment_methods">
        <h3>We accept</h3>
        <div class="payment_icons">
            <i class='bx bxl-mastercard'></i>
            <i class='bx bxl-visa'></i>
            <img src="../pics/cod.png" alt="cash on delivery">
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
    <!-- to update item quantities dynamically using ajax -->
    <script src="../JS/updatecart.js"></script>

    <script>
        // redirect to checkout page when checkout button is clicked
        window.onload = function() {
            var btn = document.querySelector('.checkout_btn');
            btn.addEventListener('click', function() {
                var couponStatus = document.getElementById('coupon_status').innerText;
                if (couponStatus !== "Coupon Invalid!") { // valid coupon
                    var couponInput = document.getElementById('coupon_input').value;
                    window.location.href = 'checkout.php?coupon=' + couponInput;
                } else { //invalid coupon
                    window.location.href = 'checkout.php';
                }
            });
        }
        // $(document).ready(function() {
        //     $('.quantity-input').on('change click', function() {
        //         $.ajax({
        //             type: 'POST',
        //             url: 'update_cart_summary.php',
        //             data: {
        //                 user_id: $('#user_id').val(), // user_id is retrieved from a hidden input field
        //             },
        //             success: function(response) {
        //                 $('.total').html(response);
        //             }
        //         });
        //     });
        // });
    </script>
    <!-- to remove items from cart using ajax -->
    <script src="../JS/delete_from_cart.js"></script>
    <!-- to coupon validity using ajax -->
    <script src="../JS/check_coupon.js"></script>
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
    <?php include_once '../PHP/footer.php'; ?>

</body>

</html>