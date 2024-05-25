<?php
function get_popular_products($conn, $nb_products = 8)
{
    $sql = "SELECT barcode, product_name, product_image, quantity, quantity_type, MIN(price) as min_price, MAX(price) as max_price 
    FROM product 
    GROUP BY barcode, product_name
    ORDER BY clicks DESC
    LIMIT $nb_products;";

    $stmt = DatabaseHelper::runQuery($conn, $sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $products = "";

    foreach ($results as $result) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($result['product_image']);

        $imageData = base64_encode($result['product_image']);
        $src = 'data:' . $mimeType . ';base64,' . $imageData;


        $products .= '<div class="productcard">';
        $products .=  '<img src=" ' . $src . '" alt="' . $result["product_name"] . '">';
        $products .=   '<h3 class="product_name">' . $result["product_name"] . '</h3>';
        $products .=   '<div class="pro_cont">';
        $products .=  '<div class="product_details">';
        if ($result['min_price'] == $result['max_price']) {
            $products .=  "<p class='product_price'> $" . $result['min_price'] . "</p>";
        } else {
            $products .=  "<p class='product_price'>$" . $result['min_price'] . " - $" .  $result['max_price'] . " </p>";
        }
        $products .=  "</div>";
        $products .=  '<a href="../products/viewproduct.php?barcode=' . $result['barcode'] . '"><span class="material-symbols-outlined">arrow_forward</span></a>';
        $products .=  '</div>';
        $products .=  '</div>';
    }
    return $products;
}
