<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Products</title>
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" /> <!-- <link rel="stylesheet" href="../CSS/popular_products.css"> -->

<style>
    .container {
  text-align: center;
  padding: 20px;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);  gap: 20px; 
 }

 .productcard {
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  padding: 15px;
  position: relative; /* Make the card the positioning context for the arrow */
  padding-top: 20px; /* Add some space for the arrow */
  padding-right: 20px;
}



.productcard img {
  width: 100%;
  height: 200px;  object-fit: cover;  
}

.productcard .product_name {
  font-weight: bold;
  margin-bottom: 5px;
}
.productcard .product_details .product_price {
    font-weight: bold;
  margin-bottom: 5px;
}
.productcard a {
    position: absolute;
  top: 10px; /* Adjust position */
  right: 15px; /* Adjust position */
  width: 30px; /* Adjust size */
  height: 30px; /* Adjust size */
  border-radius: 20px;
  color: #eee;
  background: green;
}

</style>

</head>

<body>
    <div class="container">
        <div class="product-grid">
        <?= include_once('get_popular_products.php');

        // Display the popular products
        echo get_popular_products($conn, 20);
        ?>
        </div>
    </div>
</body>

</html>