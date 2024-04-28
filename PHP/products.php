<?php

include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
// $category = 'Fruits & Vegetables'; // for testing purposes, replace with $_GET['category'] when ready
$category = str_replace('_', ' & ', $_GET['category']);
// echo $category;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $category ?></title>
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../CSS/products.css">
</head>

<body>
    <div class="container">
        <h1><?php echo $category ?></h1>
        <?php
        $sql = "SELECT product.*, supermarket.name as supermarket_name 
            FROM product 
            INNER JOIN supermarket ON product.supermarket_id = supermarket.id 
            WHERE product.category = :category";

        $stmt = DatabaseHelper::runQuery($conn, $sql, ['category' => $category]);
        // $stmt = $conn->prepare($sql);
        // $stmt->bindParam(':category', $category);
        // $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // foreach ($results as $result) {
        //     print_r(array_keys($result));
        //     echo '<br>';
        //     // rest of your code...
        // }

        foreach ($results as $result) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($result['product_image']);

            $imageData = base64_encode($result['product_image']);
            $src = 'data:' . $mimeType . ';base64,' . $imageData;

            echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '&supermarket=' . $result['supermarket_id'] . '"><div class="productcard">';
            echo '<img src="' . $src . '" alt="' . $result['product_name'] . '">'; // use $src here
            echo '<h2>' . $result['product_name'] . '</h2>';
            echo '<div class="product_details"><p>' . $result['supermarket_name'] . '</p>';
            echo '<p> $' . $result['price'] . '</p></div></a>';
            echo '<a href="addtocart.php?barcode=' . $result['barcode'] . '&supermarket=' . $result['supermarket_id'] . '"><span class="material-symbols-outlined" id="catarrow">arrow_forward</span></a>';
            echo '</div>';
        }
        ?>
    </div>

</body>

</html>