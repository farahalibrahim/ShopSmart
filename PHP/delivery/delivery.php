<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$email = 'sed.neque@icloud.edu';
$sql = "SELECT id,name FROM user WHERE email = :email";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $row['id'];
$user_name = $row['name'];

setcookie('user_id', $user_id, time() + 86400, '/');
setcookie('user_name', $user_name, time() + 86400, '/');
// time() + 86400, '/' : current unix timestamp + 24hours. '/' states that cookie is accessable thogh all the domain

// for testing, delete cookies
// setcookie('user_id', "", time() - 3600, '/');
// setcookie('user_name', "", time() - 3600, '/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Delivery</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!-- OpenStreetMap API -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // $(document).ready(function() {
        //     $("#packed_orders").click(function() {
        //         $("#content").load("packed_orders.php");
        //     });
        //     $("#out_for_delivery_orders").click(function() {
        //         $("#content").load("out_for_delivery_orders.php");
        //     });
        // });
    </script>
    <style>
        .header .navbar {
            margin-right: 20%;
        }

        .header .user {
            margin-right: 5%;
            display: flex;
        }

        .header .user .cart_account a {
            color: green;
            padding-bottom: 10px;
        }

        .header .user .cart_account .logout {
            border: 1px solid;
            border-radius: 10px;
            padding: 5px 10px;
            background: green;
            color: #eee;
        }

        div[id^='mapid'] {
            height: 200px !important;
            width: 200px !important;
            border-radius: 20px !important;
        }

        #content {
            padding: 100px 0 0 30px;
        }

        #content form input {
            color: #eee;
            border: 1px solid;
            border-radius: 10px;
            background: green;
            padding: 10px 20px;
        }

        .card-body {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card-body h5 {
            color: green;
        }

        .card-body button {
            color: #eee;
            border: 1px solid;
            border-radius: 10px;
            background: green;
            padding: 10px 20px;

        }

        .card-body input#update_status {
            border: 1px solid;
            border-radius: 15px;
            padding: 10px 20px;
            color: #eee;
            background: green;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 10;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* Could be more or less, depending on screen size */
            z-index: 10;
            position: absolute;
            /* Make the popup absolute positioned */
            top: 100px;
            /* Position the popup 100px from the top of the viewport */
            left: 50%;
            /* Center the popup horizontally */
            transform: translateX(-40%);
        }

        /* The Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    include_once 'delivery_header.php';
    ?>
    <!-- <button id="packed_orders">Packed Orders</button>
    <button id="out_for_delivery_orders">Out for Delivery Orders</button> -->
    <div id="content">
        <!-- Content will be loaded here based on button click -->
        <script>
            $(document).ready(function() {
                $("#packed_orders").click(); //initial content
            });
        </script>
    </div>
</body>

<!-- order_nb
payment_method
status
street_address
city
user_phone
payment_status
payment_card -->

</html>