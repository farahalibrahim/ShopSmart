<?php

function get_recent_products($conn, $nb_products = 8, $user_id)
{
    $sql = "SELECT product.*, MIN(product.price) as min_price, MAX(product.price) as max_price
    FROM order_details
    JOIN product ON order_details.product_barcode = product.barcode
    JOIN `order` ON order_details.order_nb = `order`.order_nb
    WHERE user_id=:user_id
    GROUP BY barcode 
    ORDER BY `order`.order_date DESC
    LIMIT $nb_products;";

    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
    $results = $stmt->fetchAll();
    $products = "";

    foreach ($results as $result) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($result['product_image']);

        $imageData = base64_encode($result['product_image']);
        $src = 'data:' . $mimeType . ';base64,' . $imageData;


        $products .= '<div class="productcard">';
        $products .=  '<img src=" ' . $src . '" alt="' . $result["product_name"] . '">';
        $products .=   '<h2 class="product_name">' . $result["product_name"] . '</h2>';
        $products .=  '<div class="product_details">';
        if ($result['min_price'] == $result['max_price']) {
            $products .=  "<p> $" . $result['min_price'] . "</p>";
        } else {
            $products .=  "<p>$" . $result['min_price'] . " - $" .  $result['max_price'] . " </p>";
        }
        $products .=  "</div>";
        $products .=  '<a href="../PHP/viewproduct.php?barcode=' . $result['barcode'] . '"><span class="material-symbols-outlined">arrow_forward</span></a>';
        $products .=  '</div>';
    }
    return $products;
}
