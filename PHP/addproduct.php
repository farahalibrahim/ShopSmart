
<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$barcode = $_POST['barcode'];
$supermarket_id = $_POST['supermarket'];
$name = $_POST['product_name'];
$manufacturer = $_POST['manufacturer'];
$category = $_POST['category'];
$img_src = $_POST['image_src'];
if ($img_src  == "url") {
    $url = $_POST['image_url_src'];
    $image_content = file_get_contents($url);
} else {
    $file_name = $_FILES["image_file_src"]["name"];
    $file_type = $_FILES["image_file_src"]["type"];
    // echo $_FILES['image_file_src']['error']; //upload error check
    //move file into save destination
    $destination = "../uploadedFiles/" . $file_name; // Corrected the destination path
    move_uploaded_file($_FILES["image_file_src"]["tmp_name"], $destination); // Corrected the file name parameter

    $image_content = file_get_contents($destination);
}
$quantity_type = $_POST['quantity_type'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$category = $_POST['category'];
$tag = $_POST['tag'];
try {

    $sql = "INSERT INTO `Product` (`barcode`, `supermarket_id`, `product_name`, `manufacturer`, `product_image`, `quantity`, `quantity_type`, `price`, `category`, `tag`)
    VALUES (:barcode, :supermarket_id, :product_name, :manufacturer, :product_image, :quantity, :quantity_type, :price, :category, :tag);";

    // Set the values for the parameters
    $params = array(
        // ':barcode' => '694174293695',
        // ':barcode' => mt_rand(100000000000, 999999999999),
        ':barcode' => $barcode,
        ':supermarket_id' => $supermarket_id,
        ':product_name' => $name,
        ':manufacturer' => $manufacturer,
        ':product_image' => $image_content,
        ':quantity' => $quantity,
        ':quantity_type' => $quantity_type,
        ':price' => $price,
        ':category' => $category,
        ':tag' => $tag
    );
    $stmt = DatabaseHelper::runQuery($conn, $sql, $params);
    header("Location: admin.php?add_product=success");
} catch (PDOException $e) {
    die($e->getMessage());
}
?>