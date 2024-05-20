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

</head>

<body>
    <div class="container">
        <?= include_once('get_popular_products.php');

        // Display the popular products
        echo get_popular_products($conn, 20);
        ?>
    </div>
</body>

</html>