<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
// $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

function getOtherProductOptions($conn, $barcode, $supermarket_id)
{
    $sql = 'SELECT product.*, supermarket.name AS  supermarket_name, supermarket.rating AS supermarket_rating  FROM product 
        INNER JOIN supermarket ON product.supermarket_id = supermarket.id
        WHERE barcode = :barcode AND supermarket_id != :supermarket_id';

    $stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode, 'supermarket_id' => $supermarket_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $products = "";
    foreach ($results as $result) {
        echo '<div class="other_product">';
        echo '<div class="supermarket_rating"><p class="product_supermarket">' . $result['supermarket_name'] . '</p>';
        if ($result['supermarket_rating'] !== null) {
            echo ' <div class="rating">';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= floor($result['supermarket_rating'])) {
                    echo '<span class="star material-symbols-rounded" style="color: yellow; font-size: 20px;">star</span>'; // Full star
                } else if ($i - 1 < $result['supermarket_rating'] && $result['supermarket_rating'] < $i) {
                    echo '<span class="star material-symbols-rounded" style="color: yellow; font-size: 20px;">star_half</span>'; // Half star
                } else {
                    echo '<span class="star material-symbols-outlined" style="color: gray; font-size: 20px;">star</span>'; // Empty star
                }
            }
            // if ($result['supermarket_rating'] - floor($result['supermarket_rating']) > 0) { // display the rating if it is decimal
            //     echo ' (' . $result['supermarket_rating'] . ')';
            // }
            echo '</div>';
        }
        echo '</div>';
        echo '<p class="other_price">$' . $result['price'] . '</p>';
        echo '<button class="add_to_list" data-barcode="' . $result['barcode'] . '" data-supermarket-id="' . $result['supermarket_id'] . '"><span class="material-symbols-outlined">playlist_add</span></button>';
        echo '<button class="add_to_cart" data-barcode="' . $result['barcode'] . '" data-supermarket-id="' . $result['supermarket_id'] . '"><span class="material-symbols-outlined">add_shopping_cart</span></button>';
        echo '</div>';
    }

    return $products;
}
