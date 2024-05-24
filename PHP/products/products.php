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
    <!-- <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin: 1% 5% 5% 5%;
        }

        .productcard {
            margin: 1%;
            padding: 5px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            border-radius: 20px;
            width: 200px;
            height: 250px;
        }

        .productcard h2,
        .productcard .product_name {
            padding-left: 10px;
            color: black;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .productcard img {
            width: 70%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .productcard:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .productcard .product_details {
            padding: 5px 10px;
            text-align: left;
        }

        .productcard .product_details .product_quantity,
        .productcard .product_details .product_price {
            font-size: 0.8em;
            /* margin: 0; */
            color: darkslategray;
        }
    </style> -->
</head>

<body>
    <!-- header -->
    <?php //include_once '../header.php'; 
    ?>
    <!-- <br><br><br><br><br><br><br><br> -->
    <h1 class="catname"><?php echo $category ?></h1>
    <div class="container">
        <?php

        $sql = "SELECT barcode, product_name, product_image,quantity, quantity_type, MIN(price) as min_price, MAX(price) as max_price 
        FROM product 
        WHERE category = :category 
        GROUP BY barcode, product_name";

        $stmt = DatabaseHelper::runQuery($conn, $sql, ['category' => $category]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


        foreach ($results as $result) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($result['product_image']);

            $imageData = base64_encode($result['product_image']);
            $src = 'data:' . $mimeType . ';base64,' . $imageData;

            echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '"><div class="productcard">';
            echo '<img src="' . $src . '" alt="' . $result['product_name'] . '">'; // use $src here
            echo '<h3 class="product_name">' . $result['product_name'] . '</h3>';
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
            echo "<p class='product_quantity'>{$quantity} {$unit}</p>";

            if ($result['min_price'] == $result['max_price']) { // only one supermarket selling product OR multiple at same price => no price range
                echo '<p class="product_price"> $' . $result['min_price'] . '</p>';
            } else { // there is a price range => multiple supermarkets selling same product
                echo '<p class="product_price"> $' . $result['min_price'] . ' - $' . $result['max_price'] . '</p>';
            }
            echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '"><span class="material-symbols-outlined" id="catarrow">arrow_forward</span></a>';
            echo '</div></div></a>';
        }
        ?>
    </div>


    </script>

    <!--Footer Section-->
    <?php include_once '../footer.php'; ?>
</body>

</html>