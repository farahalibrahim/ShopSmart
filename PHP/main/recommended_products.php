<?php
// include_once('../PHP/connection.inc.php');
// include_once('../PHP/dbh.class.inc.php');
// $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// if (isset($_COOKIE['user_id'])) {
$user_id = $_COOKIE['user_id'];
// }

$sql = "SELECT DISTINCT product.tag
FROM order_details
JOIN product ON order_details.product_barcode = product.barcode
JOIN `order` ON order_details.order_nb = `order`.order_nb
WHERE user_id=:user_id
ORDER BY `order`.order_date DESC;";

$stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
$tags = $stmt->fetchAll();

$randomProductsSql = "SELECT *, MIN(price) as min_price, MAX(price) as max_price  FROM product WHERE tag IN (";

$tagsArray = array_column($tags, 'tag');
$tagsPlaceholders = implode(',', array_fill(0, count($tags), '?'));

$randomProductsSql .= $tagsPlaceholders;
$randomProductsSql .= ")
GROUP BY barcode 
ORDER BY RAND()
LIMIT 8;";

$stmt = DatabaseHelper::runQuery($conn, $randomProductsSql, $tagsArray);
$results = $stmt->fetchAll();
$products = "";

foreach ($results as $result) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($result['product_image']);

    $imageData = base64_encode($result['product_image']);
    $src = 'data:' . $mimeType . ';base64,' . $imageData;


    $products .= '<div class="productcard">';
    $products .=  '<img src=" ' . $src . '" alt="' . $result["product_name"] . '">';
    $products .=   '<h3 class="product_name">' . $result["product_name"] . '</h3>';
    $products .=  '<div class="product_details">';
    if ($result['min_price'] == $result['max_price']) {
        $products .=  "<p class='product_price'> $" . $result['min_price'] . "</p>";
    } else {
        $products .=  "<p class='product_price'>$" . $result['min_price'] . " - $" .  $result['max_price'] . " </p>";
    }
    $products .=  "</div>";
    $products .=  '<a href="../PHP/viewproduct.php?barcode=' . $result['barcode'] . '"><span class="material-symbols-outlined">arrow_forward</span></a>';
    $products .=  '</div>';
}
return $products;
