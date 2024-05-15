<?php

include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
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
    <!-- header and footer's css -->
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../../CSS/products.css">
</head>

<body>
    <!-- header -->
    <?php include_once '../header.php'; ?>


    <div class="container">
        <h1><?php echo $category ?></h1>
        <?php
        // $sql = "SELECT product.*, supermarket.name as supermarket_name 
        //     FROM product 
        //     INNER JOIN supermarket ON product.supermarket_id = supermarket.id 
        //     WHERE product.category = :category";

        // $stmt = DatabaseHelper::runQuery($conn, $sql, ['category' => $category]);
        // // $stmt = $conn->prepare($sql);
        // // $stmt->bindParam(':category', $category);
        // // $stmt->execute();
        // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // // foreach ($results as $result) {
        // //     print_r(array_keys($result));
        // //     echo '<br>';
        // //     // rest of your code...
        // // }

        // foreach ($results as $result) {
        //     $finfo = new finfo(FILEINFO_MIME_TYPE);
        //     $mimeType = $finfo->buffer($result['product_image']);

        //     $imageData = base64_encode($result['product_image']);
        //     $src = 'data:' . $mimeType . ';base64,' . $imageData;

        // echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '&supermarket=' . $result['supermarket_id'] . '"><div class="productcard">';
        // echo '<img src="' . $src . '" alt="' . $result['product_name'] . '">'; // use $src here
        // echo '<h2>' . $result['product_name'] . '</h2>';
        // echo '<div class="product_details"><p>' . $result['supermarket_name'] . '</p>';
        // echo '<p> $' . $result['price'] . '</p></div></a>';
        // echo '<a href="addtocart.php?barcode=' . $result['barcode'] . '&supermarket=' . $result['supermarket_id'] . '"><span class="material-symbols-outlined" id="catarrow">arrow_forward</span></a>';
        // echo '</div>';
        // }

        // $sql = "SELECT * FROM product WHERE category = :category GROUP BY barcode";
        // $sql = "SELECT * FROM product WHERE category = :category";


        $sql = "SELECT barcode, product_name, product_image,quantity, quantity_type, MIN(price) as min_price, MAX(price) as max_price 
        FROM product 
        WHERE category = :category 
        GROUP BY barcode, product_name";

        $stmt = DatabaseHelper::runQuery($conn, $sql, ['category' => $category]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // foreach ($results as $result) {
        //     print_r(array_keys($result));
        //     echo '<br>';
        //     // rest of your code...
        // }

        // foreach ($results as $result) {
        //     foreach ($result as $key => $value) {
        //         if ($key != 'product_image') {
        //             echo $key . ': ' . $value . '<br>';
        //         }
        //     }
        //     echo '<br>';
        //     // rest of your code...
        // }


        foreach ($results as $result) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($result['product_image']);

            $imageData = base64_encode($result['product_image']);
            $src = 'data:' . $mimeType . ';base64,' . $imageData;

            echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '"><div class="productcard">';
            echo '<img src="' . $src . '" alt="' . $result['product_name'] . '">'; // use $src here
            echo '<h3>' . $result['product_name'] . '</h3>';
            echo '<div class="product_details">';
            $quantity = $result['quantity'];
            $quantity_type = $result['quantity_type'];
            $unit = '';

            if ($quantity >= 1000) {
                $quantity /= 1000;
                $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
            } else {
                $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
            }
            echo "<p>{$quantity} {$unit}</p>";

            if ($result['min_price'] == $result['max_price']) { // only one supermarket selling product OR multiple at same price => no price range
                echo '<p > $' . $result['min_price'] . '</p>';
            } else { // there is a price range => multiple supermarkets selling same product
                echo '<p class="prodprice"> $' . $result['min_price'] . ' - $' . $result['max_price'] . '</p>';
            }
            // echo '<p>' . $result['quantity'] . ' - $' . $result['max_price'] . '</p></div></a>';


            echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '"><span class="material-symbols-outlined" id="catarrow">arrow_forward</span></a>';
            echo '</div>';
        }
        ?>
    </div>


    </script>

    <!--Footer Section-->
    <?php include_once '../footer.php'; ?>
</body>

</html>