<?php
include_once('../PHP/connection.inc.php');
include_once('../PHP/dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Products</title>
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