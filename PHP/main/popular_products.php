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
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <style>
        .container {
            width: 100%;
            padding: 10px;
            /* Add some padding for spacing */
            margin: 0 auto;
            /* Center the container horizontally */
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
            padding: 0;
        }

        .productcard {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 10px;
            width: 200px;
            display: inline-block;
        }

        .productcard:hover {
            transform: scale(1.05);
        }

        .productcard img {
            width: 100%;
            height: 100px;
            object-fit: contain;
            object-position: center;
            border-radius: 5px;
            /* Add rounded corners */
            margin-bottom: 10px;

        }

        .productcard .product_name {
            padding-left: 10px;
            color: black;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            overflow-wrap: break-word;
            font-size: 16px;
            width: 100%;
            align-items: center;
            display: flex;
            flex: 1;

        }

        .productcard .product_details .product_price {
            margin-left: 5px;
            padding-right: 200px;
            margin-top: 10px;
            font-size: 14px;
            color: black;
            padding-left: 20px;
            width: 100px;
            display: inline-block
        }

        .productcard a {
            margin-left: 150px;
            padding-bottom: 20px;
            border-radius: 10px;

        }

        .productcard a span {
            color: #eee;
            border: 1px solid green;
            padding: 5px;
            border-radius: 20px;
            background: green;
        }
    </style>

</head>

<body>
    <?php include_once '../header.php'; ?>
    <br><br><br><br><br>
    <div class="container">
        <div class="product-grid">
            <?php
            include_once('get_popular_products.php');

            // Display the popular products
            echo get_popular_products($conn, 20);
            ?>
        </div>
    </div>
</body>

</html>