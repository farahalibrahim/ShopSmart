<?php
$sql = "SELECT product.*, supermarket.name as supermarket_name 
            FROM product 
            INNER JOIN supermarket ON product.supermarket_id = supermarket.id 
            WHERE product.barcode = :barcode AND product.supermarket_id = :supermarket_id";

$stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode, 'supermarket_id' => $supermarket_id]);

if ($row = $stmt->fetch()) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($row['product_image']);

    $imageData = base64_encode($row['product_image']);
    $src = 'data:' . $mimeType . ';base64,' . $imageData;
    echo '<img src="' . $src . '" alt="' . $row['product_name'] . '">'; // use $src here
    echo '<div class="product_details"><p>' . $row['manufacturer'] . '</p>';
    echo '<h2>' . $row['product_name'] . '</h2>';
    echo '<p>' . $row['supermarket_name'] . '</p>';
    if ($row['quantity_type'] == 'weight') {
        if ($row['quantity'] >= 1000) {
            echo '<p>' . $row['quantity'] / 1000 . ' kg </p>';
        } else if ($row['quantity'] < 1000) {
            echo '<p>' . $row['quantity'] . ' g </p>';
        }
    } else if ($row['quantity_type'] == 'piece') {
        echo '<p>' . $row['quantity']  . ' pieces</p>';
    } else if ($row['quantity_type'] == 'liquid') {
        if ($row['quantity'] >= 1000) {
            echo '<p>' . $row['quantity'] / 1000  . ' l</p>';
        } else if ($row['quantity'] < 1000) {
            echo '<p>' . $row['quantity'] . ' ml </p>';
        }
    }
    echo '<p> $' . $row['price'] . '</p>';
    echo '<button class="add_to_cart_btn" onclick="addtocart(' . $row['barcode'], $row['supermarket_id'] . ')">Add to Cart</button></div>';
} else {
    echo 'Product not found';
}
