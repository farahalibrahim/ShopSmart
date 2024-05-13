<?php

include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// $barcode = '300880128754';
// $supermarket_id = '2'; // for testing purposes

$barcode = $_GET['barcode'];
// // echo $barcode;
// $supermarket_id = $_GET['supermarket'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- header and footer's css -->
    <link rel="stylesheet" href="../CSS/header_footer.css">
    <link rel="stylesheet" href="../CSS/viewproduct.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- import AJAX jquery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />


</head>

<body>
    <!-- header -->
    <?php //include_once '../PHP/header.php'; 
    ?>

    <div class="product_container">
        <?php

        // product with same barcode accross all supermarkets selling it, inner join to include sypermarket name too
        $sql = "SELECT product.*, supermarket.name as supermarket_name 
        FROM product 
        INNER JOIN supermarket ON product.supermarket_id = supermarket.id 
        WHERE product.barcode = :barcode
        ORDER BY product.price ASC";

        $stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode]);

        // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // foreach ($results as $result) {
        //     foreach ($result as $key => $value) {
        //         if ($key != 'product_image') {
        //             echo $key . ': ' . $value . '<br>';
        //         }
        //     }
        //     echo '<br>';
        //     // rest of your code...
        // }


        // Flag variable to track whether the first row has been processed
        // use to grab product_image only once for all products
        $firstRow = true;

        echo '<div class="flex-container">';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($firstRow) {
                // Display the image for the first product
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($row['product_image']);

                $imageData = base64_encode($row['product_image']);
                $src = 'data:' . $mimeType . ';base64,' . $imageData;

                echo '<div class="product_image"><img src="' . $src . '" alt="' . $row['product_name'] . '"></div>';
                echo '<div class="product_details">';
                echo '<p>' . $row['manufacturer'] . '</p>';
                echo '<h2>' . $row['product_name'] . '</h2>';
                echo '<table class= "comparison_table" style="border: 2px solid black;">';
                $firstRow = false;
            }

            // Display the product details in a table
            echo '<tr>';
            echo '<td>' . $row['supermarket_name'] . '</td>';
            echo '<td>$' . $row['price'] . '</td>';

            //         echo '<td><button class="add_to_cart_btn" onclick="console.log(\'' . $row['barcode'] . '\', \'' . $row['supermarket_id'] . '\');">
            // <i class="bx bx-cart"></i>
            // <i class="bx bx-chevron-right"></i>
            // </button></td>';

            // add to cart button always exists yet if logged in it adds to cart if not a popup prompts to log in/sign up(in ajax file addtocart.js)
            echo '<td><button class="add_to_cart_btn" data-barcode="' . $row['barcode'] . '" data-supermarket-id="' . $row['supermarket_id'] . '">
            <i class="bx bx-cart"></i>
            <i class="bx bx-chevron-right"></i>
            </button></td>';
            echo '</tr>';
        }

        echo '</table></div></div>';

        ?>
        <!-- <button>Add to Cart</button><br> -->
        <table style="border: 2px solid black;"></table>

    </div>

    <!-- frequently bought togehter section -->
    <div class="frequently-container">

        <?php
        // get product tag to find matching products
        $sql = "SELECT DISTINCT tag FROM product WHERE barcode = :barcode";
        $stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $tag = $row['tag'];

        // get barcode's product_name
        $sql = "SELECT DISTINCT product_name FROM product WHERE barcode = :barcode";
        $stmt = DatabaseHelper::runQuery($conn, $sql, ['barcode' => $barcode]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $product = $row['product_name'];

        // retrieve max 5 products of the same tag, excluding the product itself (barcode: for same specifications, 
        // product_name: for same product different specifications(weight...)
        $sql = "SELECT barcode, product_name, product_image, MIN(price) as min_price, MAX(price) as max_price 
        FROM product 
        WHERE tag = :tag AND barcode != :barcode AND product_name != :product_name
        GROUP BY product_name 
        LIMIT 5;";

        $stmt = DatabaseHelper::runQuery($conn, $sql, ['tag' => $tag, 'barcode' => $barcode, 'product_name' => $product]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            // one related product atleast exist
            echo '<h2>Frequently Bought Together</h2>';
        }

        foreach ($results as $result) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($result['product_image']);

            $imageData = base64_encode($result['product_image']);
            $src = 'data:' . $mimeType . ';base64,' . $imageData;

            echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '"><div class="frequentcard">';
            echo '<img src="' . $src . '" alt="' . $result['product_name'] . '">'; // use $src here
            echo '<h2 class="product_name">' . $result['product_name'] . '</h2>';
            echo '<div class="product_details"><p> $' . $result['min_price'] . ' - $' . $result['max_price'] . '</p></div></a>';
            echo '<a class="prodarrow" href="viewproduct.php?barcode=' . $result['barcode'] . '"><span class="material-symbols-outlined" id="catarrow">arrow_forward</span></a>';
            echo '</div>';
        }


        ?>
    </div>
    </div>

    <script src="../JS/addtocart.js"></script>

    <input type="hidden" class="click_product" data-barcode="<?= $barcode ?>">
    <script src="../JS/track_click.js"></script>
    <script>
        $(document).ready(function() {
            $('.click_product').trigger('click');
        });
    </script>

    <!--Footer Section-->
    <?php include_once '../PHP/footer.php'; ?>
</body>

</html>