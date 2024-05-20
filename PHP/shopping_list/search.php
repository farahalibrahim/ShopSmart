<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the search variable from URL
$search = $_POST['search'];

$sql = "SELECT barcode, product_name, MIN(price) as min_price, MAX(price) as max_price, product_image, quantity, quantity_type 
        FROM product 
        WHERE `product_name` LIKE :search 
        GROUP BY barcode";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['search' => '%' . $search . '%']);

// Fetch all the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create cards for each group of products
foreach ($results as $product) {
    echo '<div class="search_card" data-barcode="' . $product['barcode'] . '">'; //data-barcode used in ajax to retrieve barcode of product once card is clicked
    // process product image
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($product['product_image']);

    $imageData = base64_encode($product['product_image']);
    $src = 'data:' . $mimeType . ';base64,' . $imageData;

    // Display image for the first product
    echo '<img src="' . $src . '" alt="' . $product['product_name'] . '">';
    // Display product details for the first product
    echo '<div class="product-details">';
    echo '<h4>' . $product['product_name'] . '</h4>';
    $quantity = $product['quantity'];
    $quantity_type = $product['quantity_type'];
    $unit = '';

    if ($quantity >= 1000) {
        $quantity /= 1000;
        $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
    } else {
        $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
    }
    echo "<p>{$quantity} {$unit}</p>";
    if ($product['min_price'] == $product['max_price']) {
        echo '<p class="price_range">$' . $product['min_price'] . '</p>';
    } else {
        echo '<p class="price_range">$' . $product['min_price'] . ' - $' . $product['max_price'] . '</p>';
    }
    echo '</div>';
    // // Display an arrow
    // echo '<div class="arrow"><span class="material-symbols-outlined">arrow_forward</span></div>';
    echo '</div>';
}
echo '<style>
#search_results .search_card {
    display: flex;
    /* border: 1px solid #ccc; */
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
    align-items: center;
}

#search_results .search_card img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    border-radius: 50%;
    margin-right: 10px;
}

#search_results .search_card .product-details {
    display: flex;
    flex-direction: column;
    justify-content: center;
}
#search_results .search_card .product-details > * {
    margin: 5px 0; 
}
</style>';
