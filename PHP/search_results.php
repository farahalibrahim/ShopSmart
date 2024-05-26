<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$search = $_GET['search']; // Retrieve the 'search' query parameter
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $search ?></title>
    <link rel="stylesheet" href="../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">


    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .container{
            padding: 2% 3% 0 3%;
        }
        
      .container .productcard{
        display: flex;
        align-items: center;
      }  
      .product_details{
        margin-left: 20px;
      }
      .product_details a {
        color: #000;
      }
      .product_details a span{
        border: 1px solid green;
        color: #eee;
        background: green;
        border-radius: 30px;
        padding: 10px;
      }
      .product_details a span:hover{
        background: #eee;
        color: green;
      }
      .productcard img{
        width: 100px;
        height: auto;
      }
      
    </style>
</head>

<body>
    <?php include_once('header.php'); ?>
    <br><br><br><br><br>
    <div class="container">
        <h2>Search results for <span id="search_query">"<?= $search ?>"</span></h2>

        <div class="c">
            <?php

            $sql = "SELECT barcode, product_name, product_image,quantity, quantity_type, MIN(price) as min_price, MAX(price) as max_price 
        FROM product WHERE product_name LIKE ?
        GROUP BY barcode, product_name";

            $stmt = DatabaseHelper::runQuery($conn, $sql, ["%$search%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($results as $result) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($result['product_image']);

                $imageData = base64_encode($result['product_image']);
                $src = 'data:' . $mimeType . ';base64,' . $imageData;

                echo '<a href="viewproduct.php?barcode=' . $result['barcode'] . '"><div class="productcard">';
                echo '<img src="' . $src . '" alt="' . $result['product_name'] . '">'; // use $src here
                
                echo '<div class="product_details">';
                echo '<h3 class="product_name">' . $result['product_name'] . '</h3>';   
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
    </div>
    
</body>
<?php include_once('footer.php'); ?>
</html>