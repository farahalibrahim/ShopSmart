<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$barcode = $_POST['barcode'];

$sql = "SELECT product.*, supermarket.name AS supermarket_name, supermarket.rating AS rating 
FROM product 
INNER JOIN supermarket ON product.supermarket_id = supermarket.id 
WHERE barcode = :barcode 
ORDER BY product.price ASC";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$first_result = 1;
$product_html = '<div class="product">';

foreach ($results as $product) {
    if ($first_result === 1) { // Display image for the first product
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($product['product_image']);

        $imageData = base64_encode($product['product_image']);
        $src = 'data:' . $mimeType . ';base64,' . $imageData;

        $product_html .= '<div class="product-image">';
        $product_html .= '<img src="' . $src . '" alt="' . $product['product_name'] . '">';
        $product_html .= '</div>';
        $product_html .= '<div class="product-details">';
        $product_html .= '<h4>' . $product['product_name'] . '</h4>';
        $quantity = $product['quantity'];
        $quantity_type = $product['quantity_type'];
        $unit = '';

        if ($quantity >= 1000) {
            $quantity /= 1000;
            $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
        } else {
            $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
        }
        $product_html .= "<p>{$quantity} {$unit}</p>";
        $product_html .= '</div>'; // Close product-details div
        $product_html .= '<table class="comparison_table">';
        $first_result++; // not first result anymore
    }

    $product_html .= '<tr>'; // Open table row
    $product_html .= '<td>' . $product['supermarket_name'];

    if ($product['rating'] !== null) {
        $product_html .= '<div class="rating">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($product['rating'])) {
                $product_html .= '<span class="star material-symbols-rounded" style="color: yellow; font-size: 20px;">star</span>'; // Full star
            } else if ($i - 1 < $product['rating'] && $product['rating'] < $i) {
                $product_html .= '<span class="star material-symbols-rounded" style="color: yellow; font-size: 20px;">star_half</span>'; // Half star
            } else {
                $product_html .= '<span class="star material-symbols-outlined" style="color: gray; font-size: 20px;">star</span>'; // Empty star
            }
        }
        $product_html .= '</div>';
    }

    $product_html .= '</td>'; // Close table cell here
    $product_html .= '<td>$' . $product['price'] . '</td>';
    $product_html .= '<td><button type="button" class="add_to_list" data-barcode="' . $product['barcode'] . '" data-supermarket-id="' . $product['supermarket_id'] . '"><span class="material-symbols-outlined">playlist_add</span></button></td>';
    $product_html .= '</tr>'; // Close table row
}

$product_html .= '</table>'; // Close table
$product_html .= '</div>'; // Close product div

echo $product_html;

echo '<style>
.product-image img {
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Shadow */
}

.product-details {
    text-align: left; /* Align to the left */
}

.product {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.comparison_table {
    border-collapse: collapse; /* Combine adjacent table borders into a single border */
    border-radius: 30px; /* Rounded corners */
    overflow: hidden; /* Hide overflow to make border-radius work */
}

.comparison_table td {
    width: auto; /* Fit content */
    border: 5px solid white; 
    padding: 10px 20px ; /* Increased padding */
    background-color: #f2f2f2; /* Light gray background color */
}

</style>';
