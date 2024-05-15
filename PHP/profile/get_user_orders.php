<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user_id = $_COOKIE['user_id'];

// Get all orders for the user
$sql = "SELECT * FROM `order` WHERE user_id = :user";
$orders = DatabaseHelper::runQuery($conn, $sql, ['user' => $user_id]);
if ($orders->rowCount() > 0) {
    // Get order details for each order
    foreach ($orders as $order) {
        $sql = "SELECT order_details.* , product.product_image, product.product_name FROM order_details 
        JOIN product ON order_details.product_barcode = product.barcode
        WHERE order_nb = :order_nb
        GROUP BY order_details.product_barcode, order_details.supermarket_id";
        $order_details = DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order['order_nb']]);

        echo '<a href="order.php?order_nb=' . $order['order_nb'] . '">';
        echo '<div class="card">';
        echo '<div class="card-header">Order# ' . $order['order_nb'] . '</div>';
        echo '<div class="card-body">';
        echo '<p>Ordered on:' . $order['order_date'] . '</p>';
        if ($order['status'] == 'delivered') {
            echo '<p>Delivered on:' . $order['order_date'] . '</p>';
        } else {
            echo '<p>Status: ' . $order['status'] . '</p>';
        }
        echo '<p>Total: ' . $order['order_total'] . '</p>';

        // Print order details as array key and value
        // foreach ($order_details as $order_detail) {
        //     foreach ($order_detail as $key => $value) {
        //         if ($key !== 'product_image') {
        //             echo '<p>' . $key . ': ' . $value . '</p>';
        //         }
        //     }
        //     echo "<br>  ";
        // }

        // Get images of products ordered

        echo '<div class="product-images">';
        $counter = 0;
        $totalItems = $order_details->rowCount();
        // echo '<p>Debug: Total items = ' . $totalItems . '</p>'; // Debug output
        foreach ($order_details as $order_detail) {
            if ($counter >= 4) {
                break;
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($order_detail['product_image']);

            $imageData = base64_encode($order_detail['product_image']);
            $src = 'data:' . $mimeType . ';base64,' . $imageData;

            echo '<img src="' . $src . '" alt="' . $order_detail['product_name'] . '">';
            $counter++;
        }

        if ($totalItems > 4) {
            echo '<p>+' . ($totalItems - 4) . ' more items</p>';
        } else {
            // echo '<p>Debug: Condition not met</p>'; // Debug output
        }

        echo '</div></div></div></a>';
        // echo '</div>';
        // echo '</div>';
    }
} else {
    echo '<div class="no-order"> <span class="material-symbols-outlined">orders</span>';
    echo '<h2>No Credit Cards Found</h2>';
    echo '<p>Use the button above to add</p></div>';
}
