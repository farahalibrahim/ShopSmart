<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$search_attr = $_POST['searchType'];
if (isset($_POST['searchSelectValue'])) {
    $search_value = $_POST['searchSelectValue'];
} else if (isset($_POST['searchInputValue'])) {
    $search_value = $_POST['searchInputValue'];
}

if ($search_attr == 'barcode') {
    $sql = "SELECT product.*, MIN(price) as min_price, MAX(price) as max_price FROM product WHERE barcode LIKE :barcode GROUP BY barcode";
    $attr = ["barcode" => $search_value . "%"];
} else if ($search_attr == 'product_name') {
    $sql = "SELECT product.*, MIN(price) as min_price, MAX(price) as max_price FROM product WHERE product_name LIKE :product_name GROUP BY barcode";
    $attr = ["product_name" => "%" . $search_value . "%"];
} else if ($search_attr == 'supermarket_name') {
    $sql = "SELECT product.*, MIN(price) as min_price, MAX(price) as max_price FROM product INNER JOIN supermarket  ON product.supermarket_id = supermarket.id WHERE supermarket.name LIKE :supermarket_name GROUP BY barcode";
    $attr = ["supermarket_name" => $search_value . "%"];
} else if ($search_attr == 'category') {
    $sql = "SELECT product.*, MIN(price) as min_price, MAX(price) as max_price FROM product WHERE category = :category GROUP BY barcode";
    $attr = ["category" => $search_value];
} else if ($search_attr == 'tag') {
    $sql = "SELECT product.*, MIN(price) as min_price, MAX(price) as max_price FROM product WHERE tag = :tag GROUP BY barcode";
    $attr = ["tag" => $search_value];
}

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    $stmt = DatabaseHelper::runQuery($conn, $sql, $attr);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $product) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($product['product_image']);

        $imageData = base64_encode($product['product_image']);
        $src = 'data:' . $mimeType . ';base64,' . $imageData;

        $quantity = $product['quantity'];
        $quantity_type = $product['quantity_type'];
        $unit = '';

        if ($quantity >= 1000) {
            $quantity /= 1000;
            $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
        } else {
            $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
        }

        echo '<div class="card">';
        echo '<div class="card-top">';
        echo '<img src="' . $src . '" alt="' . $product['product_name'] . '">';
        echo '<div class="card-body">';
        echo '<h3>' . $product['product_name'] . '</h3>';
        echo '<p>' . $product['manufacturer'] . '</p>';
        echo "<p class='product_quantity'>{$quantity} {$unit}</p>";
        if ($product['min_price'] == $product['max_price']) { // only one supermarket selling product OR multiple at same price => no price range
            echo '<p class="product_price"> $' . $product['min_price'] . '</p>';
        } else { // there is a price range => multiple supermarkets selling same product
            echo '<p class="product_price"> $' . $product['min_price'] . ' - $' . $product['max_price'] . '</p>';
        }
        echo '</div></div>';
        echo '<div class="card-bottom">';
        echo '<details class="product_options">';
        echo '<summary>Supermarkets offering products</summary>';

        $sql = "SELECT supermarket.name, product.supermarket_id, price FROM product INNER JOIN supermarket ON product.supermarket_id = supermarket.id WHERE barcode = :barcode";
        $attr = ["barcode" => $product['barcode']];
        $supermarket_stmt = DatabaseHelper::runQuery($conn, $sql, $attr);
        $results = $supermarket_stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<table  class="product_options_table">';
        foreach ($results as $supermarket) {
            echo '<tr class="product_option">';
            echo '<td>' . $supermarket['name'] . '</td>';
            echo '<td>$' . $supermarket['price'] . '</td>';
            echo '<td><button class="edit-button" data-barcode="' . $product['barcode'] . '" data-supermarket-id="' . $supermarket['supermarket_id'] . '"><span class="material-symbols-outlined">edit</span></button></td>';
            echo '<td><button class="delete-button" data-barcode="' . $product['barcode'] . '" data-supermarket-id="' . $supermarket['supermarket_id'] . '"><span class="material-symbols-outlined">delete</span></button></td>';
            echo '<td><button class="offer-button" data-barcode="' . $product['barcode'] . '" data-supermarket-id="' . $supermarket['supermarket_id'] . '"><span class="material-symbols-outlined">price_change</span></button></td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</details>';
        echo '</div></div>';
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
