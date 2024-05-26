<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
$order = $_GET['order_nb'];
$user_id = $_COOKIE['user_id'];
$sql = 'SELECT `role` FROM `user` WHERE id = :user_id';
$stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
$role = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Order# " . $order; ?></title>
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
       
        .order_items {
            border: 2px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 0px;
            /* Add rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            /* Add shadow */
            
        }
        .order_items .supermarket_items{
            padding: 0 5% 0 5%;
            
        }
        .order_items .supermarket_items .stars{
            margin-bottom: 10px;
        }
        .order_items h3 span{
            padding-left: 45%;
            color: green;
        }
        .card{
            padding: 0 5% 0 5%;
            
        }
        .card-header{
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: none;
            padding: 10px;
            background: green;
        }
        .card-header h3{
            padding-right: 5%;
            font-weight: 0;
        }
        .card-header h2{
            padding-left: 5%;
        }
        .status{
            border-radius: none;
            padding: 0;
            background: lightgray;
            padding-left: 5%;
        }
        .status i{
            font-size: 30px;
        }
        .status p {
            font-size: 20px;
        }
        .address_contact{
            border-radius: none;
            padding: 10px;
            /* background: linear-gradient(to bottom, #7CE200 0%, #4c4c4c 100%); */
            background: lightgray;
        }

        .item-card {
            display: flex;
            /* Use Flexbox */
            align-items: center;
            /* Vertically align the image and item details */
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            /* Add rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            /* Add shadow */
        }

        .item-card img {
            width: 100px;
            /* Adjust as needed */
            height: 100px;
            /* Adjust as needed */
            object-fit: contain;
            /* Resize the image to fit the dimensions */
            margin-right: 10px;
            /* Add some space between the image and the item details */
        }

        .item-details {
            flex-grow: 1;
            /* Allow the item details to take up the remaining space */
        }

        /* Add some CSS to style the stars */
        .stars {
            display: flex;
        }

        .star {
            cursor: pointer;
            color: transparent;
            /* Make the text color transparent */
            text-shadow: 0 0 0 gray;
            /* Add a gray shadow to the text */
            /* border: 1px solid yellow; */
            /* Add a yellow border */
        }

        .star.active {
            text-shadow: 0 0 0 yellow;
            /* Change the shadow color to yellow when active */
        }

        .status,
        .address
         {
            display: flex;
            align-items: center;
        }
        .payment_method{
            display: flex;
            margin: 0;
        }

        .status_icon,
        .address_icon,
        .payment_icon {
            margin-right: 10px;
        }
        .payment .payment_method{
            border: 1px solid;
            border-radius: none;
            background: green;
            padding: 5px;
        }
        .payment .payment_method p{
            margin-left: 5%;
            font-size: 20px;
            font-weight: 400;
        }
        .payment .payment_method span {
            margin-left: 5%;
            margin-top: 20px;
        }
        
    </style>
</head>

<body>
    <br><br><br><br><br>
    <?php
    include_once '../header.php';
    // Get the order details
    $query = "SELECT `order`.*,order_details.*,shipment.*, COUNT(order_details.order_nb) as nb_products, order.status AS `status`, supermarket.name AS supermarket_name,product.quantity_type, product.quantity AS product_quantity, product.product_name, product.product_image FROM `order`
              JOIN order_details ON order.order_nb = order_details.order_nb 
              JOIN shipment ON order.order_nb = shipment.order_nb 
              JOIN supermarket ON order_details.supermarket_id = supermarket.id 
              JOIN product ON order_details.product_barcode = product.barcode 
              WHERE order.order_nb = :order_nb
              GROUP BY order_details.product_barcode, order_details.supermarket_id";
    $stmt = DatabaseHelper::runQuery($conn, $query, ['order_nb' => $order]);
    $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="card">'; // Start the card
    echo '<div class="card-header"><h2>Order# ' . $order . '</h2>'; // Display the order number
    echo '<h3><span>Ordered on: ' . $orderDetails[0]['order_date'] . '</span></h3></div>';

    echo '<div class="status">';
    if ($orderDetails[0]['status'] == 'delivered') {
        echo '<i class="bx bx-check status_icon"></i><p class="status">Delivered on: ' . $orderDetails[0]['order_date'] . '</p>'; // Display the delivery date
    } else if ($orderDetails[0]['status'] == 'cancelled') {
        echo '<i class="bx bx-x status_icon" ></i><p class="status">Cancelled</p>'; // Display the cancellation date
    } elseif ($orderDetails[0]['status'] == 'processing') {
        echo '<i class="bx bx-store status_icon"></i> <p class="status">Processing</p>'; // Display the processing status\
    } elseif ($orderDetails[0]['status'] == 'out_for_delivery') {
        echo '<span class="material-symbols-outlined status_icon">local_shipping</span> <p class="status">Out for Delivery</p>'; // Display the shipping status
    } elseif ($orderDetails[0]['status'] == 'packed') {
        echo '<i class="bx bx-package status_icon"></i> <p class="status">Packed</p>'; // Display the shipping status
    }
    // echo  $orderDetails[0]['status'];
    echo '</div>';

    echo '<div class="address_contact">';
    echo '<h2>Delivery Address & Contact:</h2>';
    echo '<div class="address"><span class="material-symbols-outlined address_icon">door_open</span>';
    echo '<p>' . $orderDetails[0]["street_address"] . ' - ' . $orderDetails[0]["city"] . '</p></div>';
    echo '<div class="contact"><p>' . $orderDetails[0]["user_phone"] . '</p></div>';
    echo '</div>';

    // Create a new array that groups the items by supermarket_id
    $groupedItems = [];
    foreach ($orderDetails as $item) {
        if (!isset($groupedItems[$item['supermarket_name']])) {
            $groupedItems[$item['supermarket_name']] = [
                'items' => [],
                'id' => $item['supermarket_id'] // Assuming 'supermarket_id' is available in $item
            ];
        }
        $groupedItems[$item['supermarket_name']]['items'][] = $item;
    }

    echo '<div class="order_items">'; // Parent card

    echo '<h3 id="orderItems"> <span>Order</span> Items</h3>';

    echo '<span>(' . $orderDetails[0]["nb_products"] . ' items)</span>';

    // Loop over the supermarkets
    foreach ($groupedItems as $supermarketName => $supermarketData) {
        $supermarketId = $supermarketData['id']; // Get the supermarket ID
        $items = $supermarketData['items'];


        echo '<div class="supermarket_items" data-id="' . $supermarketId . '">'; // Supermarket items div
        echo '<h4>' . $supermarketName . '</h4>'; // Display the supermarket ID

        // Display the rating stars if user, (admin is also redirected to same page)
        if ($role == 'user') {
            echo '<div class="stars">';
            for ($i = 1; $i <= 5; $i++) {
                echo '<span class="star" data-value="' . $i . '">&#9734;</span>'; // Display an empty star
            }
            echo '</div>';
        }

        // Loop over the items in the order
        foreach ($items as $item) {
            // process product image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($item['product_image']);

            $imageData = base64_encode($item['product_image']);
            $src = 'data:' . $mimeType . ';base64,' . $imageData;

            echo '<div class="item-card">'; // Item card
            echo '<img src="' . $src . '" alt="' . $item['product_name'] . '">'; // Display the product image
            echo '<div class="item-details"><h3>' . $item['product_name'] . '</h3>'; // Display the product name
            $quantity = $item['product_quantity'];
            $quantity_type = $item['quantity_type'];
            $unit = '';

            if ($quantity >= 1000) {
                $quantity /= 1000;
                $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
            } else {
                $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
            }
            $quantity *= $item['quantity']; // Calculate the total quantity
            echo "<p>{$quantity} {$unit}</p>";

            // echo '<p>Quantity: ' . $item['quantity'] . '</p>'; // Display the quantity
            echo '<p>Price: $' . $item['price'] . '</p>'; // Display the price
            echo '</div></div>'; // End of item card
        }
        echo '</div>'; // End of supermarket items div
    }
    echo '</div>'; // End of parent card



    echo '<div class="payment">';
    echo '<div class="payment_method">';
    if (($orderDetails[0]['payment_method'] == 'card')) {
        echo '<span class="material-symbols-outlined payment_icon">credit_card</span>';
        echo '<p>Card ending in ' . substr($orderDetails[0]['payment_card'], -4) . '</p>'; // Display the card number
    } else if (($orderDetails[0]['payment_method'] == 'cod')) {
        echo '<span class="material-symbols-outlined payment_icon">payments</span>';
        echo '<p>Cash on Delivery</p>'; // Display the card number
    }
    echo '<p>$' . $orderDetails[0]['order_total'] . '</p>';
    echo '</div>';


    // // Print order details as array key and value
    // foreach ($orderDetails as $order_detail) {
    //     foreach ($order_detail as $key => $value) {
    //         if ($key !== 'product_image') {
    //             echo '<p>' . $key . ': ' . $value . '</p>';
    //         }
    //     }
    //     echo "<br>  ";
    // }

    // Display the order details
    // if ($orderDetails) {
    //     echo "Order Number: " . $orderDetails['order_nb'] . "<br>";
    //     echo "Order Date: " . $orderDetails['order_date'] . "<br>";
    //     // Display other order details as needed
    // } else {
    //     echo "Order not found.";
    // }
    ?>
    <script>
        // ...

        $(document).ready(function() {
            $('.star').click(function() {
                // Get the rating value
                var value = $(this).data('value');

                // Get the supermarket ID
                var supermarketId = $(this).closest('.supermarket_items').data('id');
                // Add the 'active' class to the clicked star and all its older siblings
                $(this).prevAll().addClass('active');
                $(this).addClass('active');

                // Remove the 'active' class from the younger siblings
                $(this).nextAll().removeClass('active');

                // Send an AJAX request to a PHP script
                $.ajax({
                    url: 'save_rating.php', // The URL of the PHP script
                    method: 'POST', // The HTTP method
                    data: { // The data to send
                        supermarket_id: supermarketId,
                        rating: value
                    },
                    success: function(response) {
                        // Handle the response
                        console.log(response);
                    }
                });
            });
        });

        // ...
    </script>
</body>

</html>