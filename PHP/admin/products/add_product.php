<?php
include_once('../../connection.inc.php');
include_once('../../dbh.class.inc.php');
// Retrieve the form data
$barcode = $_POST['barcode'];
$supermarketId = $_POST['supermarket'];
$productName = $_POST['product_name'];
$manufacturer = $_POST['manufacturer'];
$quantity = $_POST['quantity'];
$quantityType = $_POST['quantity_type'];
$price = $_POST['price'];
$expiryDate = $_POST['expiry_date'];
$category = $_POST['category'];
$tag = $_POST['tag'];
$offer = 0; // no offer by default

$productImage = $_FILES['product_image'];
$nutritionalFacts = $_FILES['nutritional_facts'];

$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Check if product already exists
$query = DatabaseHelper::runQuery($conn, "SELECT * FROM product WHERE barcode = :barcode AND supermarket_id = :supermarket_id", ['barcode' => $barcode, 'supermarket_id' => $supermarketId]);

if ($query->fetch()) {
    // If product exists, echo error message and exit
    echo "Product already exists at selected supermarket";
    exit;
}

// Process the image and nutritional facts files
$target_dir = "uploads/";

$productImageExtension = pathinfo($productImage["name"], PATHINFO_EXTENSION);
$nutritionalFactsExtension = pathinfo($nutritionalFacts["name"], PATHINFO_EXTENSION);

$productImageExtension = pathinfo($productImage["name"], PATHINFO_EXTENSION);
$nutritionalFactsExtension = pathinfo($nutritionalFacts["name"], PATHINFO_EXTENSION);

// Check if the files are images
if (!getimagesize($productImage["tmp_name"]) || !getimagesize($nutritionalFacts["tmp_name"])) {
    echo "Only images are allowed";
    exit;
}


$productImagePath = $target_dir . "image-" . $barcode . "." . $productImageExtension;
$nutritionalFactsPath = $target_dir . "nutritional-" . $barcode . "." . $nutritionalFactsExtension;

// overwrite the files if they already exist
move_uploaded_file($productImage["tmp_name"], $productImagePath);
move_uploaded_file($nutritionalFacts["tmp_name"], $nutritionalFactsPath);


// Convert the files to binary data
$productImageData = file_get_contents($productImagePath);
$nutritionalFactsData = file_get_contents($nutritionalFactsPath);
$nutritionalFacts = base64_encode($nutritionalFactsData); //existing data is base64 encoded and retrieved in user as already encoded

// Insert the new product into the database
$attr = [
    'barcode' => $barcode,
    'supermarket_id' => $supermarketId,
    'product_name' => $productName,
    'manufacturer' => $manufacturer,
    'product_image' => $productImageData,
    'nutritional_facts' => $nutritionalFacts,
    'quantity' => $quantity,
    'quantity_type' => $quantityType,
    'price' => $price,
    'expiry_date' => $expiryDate,
    'category' => $category,
    'tag' => $tag,
    'offer' => $offer
];
$sql = "INSERT INTO product (barcode, supermarket_id, product_name, manufacturer, product_image, nutritional_facts, quantity, quantity_type, price, expiry_date, category, tag, offer) VALUES (:barcode, :supermarket_id, :product_name, :manufacturer, :product_image, :nutritional_facts, :quantity, :quantity_type, :price, :expiry_date, :category, :tag, :offer)";

$stmt = DatabaseHelper::runQuery($conn, $sql, $attr);

if ($stmt->rowCount() > 0) {
    echo "Product added successfully";
} else {
    echo "Product wasn't added";
}
