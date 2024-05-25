<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE['user_id'];

include_once '../responseModal.inc.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= explode(' ',  $_COOKIE['user_name'])[0] . "'s Shopping List" ?></title>
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Google Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>

        
        

        .container {

            padding: 20px;
            border-radius: 10px;
            /* Add rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            /* background: lightgrey; */
        }

        .add_button {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        button:hover {
            border: green 2px solid;
            background-color: white;
            color: black;
        }

        button {
            display: flex;
            align-items: center;
            justify-content: center;

            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 70px;
            margin-right: 10px;
            cursor: pointer;
        }

        /* button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 70px;
            margin-right: 10px;
            cursor: pointer;
        } */

        button>*:last-child {
            margin-right: 0;
        }

        button>* {
            margin-right: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .price_increase {
            color: red;
        }

        .price_decrease {
            color: green;
        }

        .price_same {
            color: blue;
        }

        .current_price {
            font-size: 20px;
            font-weight: bold;
        }

        .list_item {
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            padding: 20px;
            margin-bottom: 20px;
            /* Add margin between list items */
            /* background: #eee; */
        }

        .product {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* Distribute elements evenly */
            margin-bottom: 20px;
        }

        .product img {
            border-radius: 10px;
            object-fit: contain;
            margin-right: 20px;
            width: 100px;
            height: 100px;
            margin-right: 20px;
        }

        .product_details>* {
            margin-bottom: 5px;
            margin-top: 0;
        }

        .product_details {
            flex-grow: 1;
            /* margin-right: 100px; */
        }

        .price_indicator {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .price_indicator>* {
            margin-right: 10px;
        }

        .price_indicator>*:first-child {
            margin-right: 5px;
        }

        .price_indicator>*:last-child {
            margin-right: 20px;
        }

        .other_products {
            display: flex;
            flex-direction: column;
        }

        .other_products>h3 {
            margin-left: 10px;
            margin-bottom: 10px;
        }

        .other_product {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            margin-left: 30px;
        }

        .other_product>* {
            margin-right: 10px;
        }

        .other_product>*:last-child {
            margin-right: 0;
        }

        table {
            table-layout: fixed;
            /* width: 100%;  */
        }

        td {
            border: #000 1px solid;
            /* width: max-content; */
            /* Change this line */
            /* padding: 10px; */
        }
    </style>
</head>

<body>
    <!-- redirected -->
    <br><br><br><br><br>
    <?php include_once '../header.php'; ?>
    <div class="container">
        <h1>Shopping List</h1>
        <div class="add_button"><button type="button" id="add_item"><span class="material-symbols-outlined">playlist_add</span> <span>Add Item</span></button></div>
        <?php
        $sql = "SELECT shopping_list.*, shopping_list.price AS saved_price, product.product_name, product.price AS current_price, product.product_image, product.quantity, product.quantity_type,
        supermarket.name AS supermarket_name, supermarket.rating AS supermarket_rating FROM shopping_list 
            INNER JOIN product ON shopping_list.product_barcode = product.barcode AND shopping_list.supermarket_id = product.supermarket_id
            INNER JOIN supermarket ON shopping_list.supermarket_id = supermarket.id
            WHERE shopping_list.user_id = :user_id";
        $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$row) {
            echo "<div class='no-list'>";
            echo "<span class='material-symbols-outlined'>subject</span>";
            echo "<h1>Shopping List is empty</h1>";
            echo "<p>Start adding from above</p></div>";
        } else {
            foreach ($row as $result) {
                // foreach ($result as $key => $value) {
                //     if ($key != 'product_image') {
                //         echo $key . ": " . $value . "<br>";
                //     }
                // }
                // echo "<hr>"; // Add a horizontal line for readability
                echo '<div class="list_item">';

                //process product image after retrieve
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($result['product_image']);

                $imageData = base64_encode($result['product_image']);
                $src = 'data:' . $mimeType . ';base64,' . $imageData;

                echo '<div class="product">';
                echo '<img class="product_image" src="' . $src . '"></img>';
                echo '<div class="product_details">';
                echo '<h2 class="product_name">' . $result['product_name'] . '</h2>';
                echo '<div class="supermarket_rating"><p class="product_supermarket">' . $result['supermarket_name'] . '</p>';
                if ($result['supermarket_rating'] !== null) {
                    echo ' <div class="rating">';
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= ceil($result['supermarket_rating'])) {
                            echo '<span class="star" style="color: yellow;">&#9733;</span>'; // Full star
                        } else {
                            echo '<span class="star" style="color: gray;">&#9734;</span>'; // Empty star
                        }
                    }
                    if ($result['supermarket_rating'] - floor($result['supermarket_rating']) > 0) { // display the rating if it is decimal
                        echo ' (' . $result['supermarket_rating'] . ')';
                    }
                    echo '</div>';
                }
                echo '</div>';
                $quantity = $result['quantity'];
                $quantity_type = $result['quantity_type'];
                $unit = '';

                if ($quantity >= 1000) {
                    $quantity /= 1000;
                    $unit = ($quantity_type == 'weight') ? 'kg' : (($quantity_type == 'liquid') ? 'L' : 'pieces');
                } else {
                    $unit = ($quantity_type == 'weight') ? 'g' : (($quantity_type == 'liquid') ? 'ml' : 'pieces');
                }
                echo "<p class='product_quantity'>{$quantity} {$unit}</p></div>";
                echo '<div class="price_indicator">';
                $savedPrice = $result['saved_price'];
                $currentPrice = $result['current_price'];
                $priceDifference = abs($savedPrice - $currentPrice);

                if ($currentPrice > $savedPrice) {
                    echo '<span class="material-symbols-outlined price_increase">arrow_upward</span>';
                    echo "<p class='price_difference price_increase'>(+ \${$priceDifference} )</p>";
                    $priceIndicatorClass = 'price_increase';
                } elseif ($currentPrice < $savedPrice) {
                    echo '<span class="material-symbols-outlined price_decrease">arrow_downward</span>';
                    echo "<p class='price_difference price_decrease'>(- \${$priceDifference} )</p>";
                    $priceIndicatorClass = 'price_decrease';
                } else {
                    echo '<span class="material-symbols-outlined price_same">drag_handle</span>';
                    $priceIndicatorClass = 'price_same';
                }

                echo "<p class='current_price {$priceIndicatorClass}'>\${$currentPrice}</p>";
                echo '</div>';
                echo '<button type="button" class="remove_from_list" data-barcode="' . $result['product_barcode'] . '" data-supermarket-id="' . $result['supermarket_id'] . '"><span class="material-symbols-outlined">playlist_remove</span></button>';
                echo '<button type="button" class="add_to_cart" data-barcode="' . $result['product_barcode'] . '" data-supermarket-id="' . $result['supermarket_id'] . '"><span class="material-symbols-outlined">add_shopping_cart</span></button>';
                echo '</div>';
                include_once 'other_options.php';
                echo "<div class='other_products'><h3>Other Options</h3>";
                $otherProductOptions = getOtherProductOptions($conn, $result['product_barcode'], $result['supermarket_id']);
                echo "{$otherProductOptions}</div>";
                echo '</div>';
            }
        }
        ?>
    </div>

    <script src="../../JS/addtocart.js"></script>
    <script src="../../JS/add_to_shopping_list.js"></script>
</body>
<div id="ShoppingListModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <!-- Search bar -->
        <form action="#">
            <input type="search" id="searchBar" name="searchBar" placeholder="Search...">
            <div id="search_results" style="display: none;">Search results will appear here</div>
            <button type="submit"><span class="material-symbols-outlined">search</span></button>
        </form>
        <div class="products"></div>
    </div>

</div>

</html>
<script>
    // Show shopping_list modal
    $(document).ready(function() {
        var modal = document.getElementById("ShoppingListModal");
        var btn = document.getElementById("add_item");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
    // dynamic Search for products
    $(document).ready(function() {
        $("#searchBar").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $.ajax({
                url: 'search.php',
                type: 'post',
                data: {
                    search: value
                },
                success: function(result) {
                    if (result) { // if there are results
                        $("#search_results").css("display", "block"); // show the search results
                    } else {
                        $("#search_results").css("display", "none"); // hide the search results
                    }
                    $("#search_results").html(result);

                    // Add click event to search cards
                    $(".search_card").click(function() {
                        var barcode = $(this).data("barcode");
                        var productName = $(this).find(".product-details h4").text();

                        // Set the product name as the value of the search input
                        $("#searchBar").val(productName);

                        // Fetch all products with the same barcode
                        $.ajax({
                            url: 'get_search_products.php',
                            type: 'post',
                            data: {
                                barcode: barcode
                            },
                            success: function(result) {
                                $(".products").html(result);
                            }
                        });

                        // Hide the search results after clicking a result
                        $("#search_results").css("display", "none");
                    });
                }
            });
        });
    });
    // Remove product from shopping list
    $(document).on('click', '.remove_from_list', function() {
        var barcode = $(this).data("barcode");
        var supermarket_id = $(this).data("supermarket-id");

        // Send AJAX request to remove_from_shopping_list.php
        $.ajax({
            url: 'remove_from_shopping_list.php',
            type: 'post',
            data: {
                barcode: barcode,
                supermarket_id: supermarket_id
            },
            success: function(result) {
                showResponseModal("Product removed from shopping list", function() {
                    location.reload();
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showResponseModal("Failed to remove product from shopping list: " + errorThrown);
            }
        });
    });
</script>