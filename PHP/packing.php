<?php
include_once('../PHP/connection.inc.php');
include_once('../PHP/dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$email = 'placerat@icloud.edu';
$sql = "SELECT id,name FROM user WHERE email = :email";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $row['id'];
$user_name = $row['name'];

setcookie('user_id', $user_id, time() + 86400, '/');
setcookie('user_name', $user_name, time() + 86400, '/');
// time() + 86400, '/' : current unix timestamp + 24hours. '/' states that cookie is accessable thogh all the domain

// for testing, delete cookies
// setcookie('user_id', "", time() - 3600, '/');
// setcookie('user_name', "", time() - 3600, '/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../CSS/header_footer.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.logout').click(function() {
                $.post('../PHP/logout.php', function() {
                    window.location.href = 'http://localhost:3000/HTML/login.html'; // Redirect to the login page
                });
            });
        });
    </script>
    <style>
        .order {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .order h2,
        .order h3 {
            margin-bottom: 10px;
        }

        .item {
            display: flex;
            align-items: center;
            justify-content: start;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .item input[type="checkbox"] {
            float: left;
            margin-right: 10px;
        }

        .item img {
            float: left;
            margin-right: 10px;
            width: auto;
            height: 150px;
            object-fit: cover;
        }

        .item-details {
            overflow: hidden;
            /* to clear the float */
        }

        .item-details p {
            margin: 0;
        }
    </style>
</head>

<body>
    <?php include 'packing_header.php'; ?>
    <?php
    // Get all orders that are currently being processed and have not been packed yet
    $query = "SELECT * FROM `order` WHERE `status` = 'processing' ORDER BY order_date ASC";
    $stmt = DatabaseHelper::runQuery($conn, $query);

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $order) {
            echo "<div class='order' data-order='" . $order['order_nb'] . "'>";
            echo "<h2>Order# " . $order['order_nb'] . "</h2>";
            // echo "<h3>Customer: " . $order['user_id'] . "</h3>";
            echo "<h3>Order Date: " . $order['order_date'] . "</h3>";
            // echo "<h3>Order Status: " . $order['status'] . "</h3>";
            echo "<h3>Order Items:</h3>";

            // Get all items in the order
            $query = "SELECT order_details.quantity AS order_quantity, 
                             product.quantity AS product_quantity, 
                             order_details.product_barcode, 
                             order_details.supermarket_id, 
                             product.product_name, 
                             product.quantity_type, 
                             product.product_image,
                             product.manufacturer,
                             supermarket.name AS supermarket
                      FROM order_details 
                      JOIN product ON order_details.product_barcode = product.barcode 
                      AND order_details.supermarket_id = product.supermarket_id 
                      JOIN supermarket ON order_details.supermarket_id = supermarket.id
                      WHERE order_nb = :order";
            $stmt = DatabaseHelper::runQuery($conn, $query, ['order' => $order['order_nb']]);

            if ($stmt->rowCount() > 0) {
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
                    // process product image
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->buffer($item['product_image']);

                    $imageData = base64_encode($item['product_image']);
                    $src = 'data:' . $mimeType . ';base64,' . $imageData;

                    echo "<div class='item'>";
                    echo "<input type='checkbox' id='product-" . $item['product_barcode'] . "' name='product-" . $item['product_barcode'] . "'>";
                    echo "<img src='" . $src . "' alt='Product Image'>";
                    echo "<div class='item-details'>";
                    echo "<h4>" . $item['product_name'] . "</h4>";
                    echo "<p>Manufacturer: " . $item['manufacturer'] . "</p>";
                    echo "<p>Barcode: " . $item['product_barcode'] . "</p>";
                    echo "<p>Supermarket: " . $item['supermarket'] . "</p>";
                    $quantity = $item['product_quantity'];
                    $quantity_type = $item['quantity_type'];
                    $unit = '';

                    if ($quantity >= 1000) {
                        $quantity /= 1000;
                        $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
                    } else {
                        $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
                    }
                    echo "<p>Unit: {$quantity} {$unit}</p>";
                    echo "<p>Quantity: " . $item['order_quantity'] . "</p>";
                    echo "</div></div>";
                }
            } else {
                echo "<p>No items found.</p>";
            }

            echo "<button class='pack-order' disabled>Pack Order</button>";
            echo "</div>";
        }
    } else {
        echo "No orders found.";
    }

    ?>
    <script src="../JS/pack_order.js"></script>
</body>

</html>