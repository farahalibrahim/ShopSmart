<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$sql = "SELECT product.*, supermarket.name AS supermarket_name FROM product INNER JOIN supermarket ON product.supermarket_id = supermarket.id WHERE offer = 1";
$stmt = DatabaseHelper::runQuery($conn, $sql);
$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
// foreach ($offers as $offer) {
//     foreach ($offer as $key => $value) {
//         if ($key !== 'product_image') {
//             echo $key . " : " . $value . "<br>";
//         }
//     }
//     echo "<br>";
// }
foreach ($offers as $offer) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($offer['product_image']);

    $imageData = base64_encode($offer['product_image']);
    $src = 'data:' . $mimeType . ';base64,' . $imageData;

    echo '<div class="card">';
    echo '<img src="' . $src . '" alt="' . $offer['product_name'] . '">';
    echo '<div class="card-body">';
    echo '<h3 class="product_name">' . $offer['product_name'] . '</h3>';
    echo '<div class="info"><p>' . $offer['supermarket_name'] . '</p>';
    $quantity = $offer['quantity'];
    $quantity_type = $offer['quantity_type'];
    $unit = '';

    if ($quantity >= 1000) {
        $quantity /= 1000;
        $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
    } else {
        $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
    }
    echo "<p>{$quantity} {$unit}</p>";

    echo '<div class="price"><p class="new_price">$' . $offer['price'] . '</p>';
    echo '<p class="old_price"><span style="text-decoration: line-through;">$' . $offer['original_price'] . '</span></p></div></div>';

    // add to cart button always exists yet if logged in it adds to cart if not a popup prompts to log in/sign up(in ajax file addtocart.js)
    echo '<button class="add_to_cart" data-barcode="' . $offer['barcode'] . '" data-supermarket-id="' . $offer['supermarket_id'] . '">
    <span class="material-symbols-outlined">add_shopping_cart</span>
    </button>';
    echo '</div>';
    echo '</div>';
}
