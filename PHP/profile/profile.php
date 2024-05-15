<?
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$user_id = $_COOKIE['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= explode(' ', $_COOKIE['user_name'])[0] . "'s Profile" ?></title>
    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Google Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- OpenStreetMap API -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        $(document).ready(function() {
            // Function to load content via AJAX
            function loadContent(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#content').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

            // Event handlers for button clicks
            $('#orders').click(function() {
                loadContent('get_user_orders.php');
            });

            $('#address').click(function() {
                loadContent('address.php');
            });

            $('#credit_cards').click(function() {
                loadContent('credit_cards.php');
            });

            $('#settings').click(function() {
                loadContent('settings.php');
            });

            $('#logout').click(function() {
                $.ajax({
                    url: '../logout.php',
                    type: 'POST',
                    success: function(response) {
                        // Redirect to index.php
                        window.location.href = '../main/index.php';
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
    <style>
        /* The Modal (background) */
        .modal,
        #addCardModal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            /* background-color: rgb(0, 0, 0); */
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content,
        #addressModal .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Modal Close Button */
        .close,
        #addressModal .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus,
        #addressModal .close:hover,
        #addressModal .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        /* Form styling */
        #addressModal form {
            display: flex;
            flex-direction: column;
        }

        #addressModal label {
            margin-bottom: 5px;
        }

        #addressModal input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #addressModal button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #addressModal button:hover {
            background-color: #45a049;
        }

        .card,
        .address-card {
            /* border: 1px solid #ccc; */
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            /* Add rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            /* Add shadow */
        }

        .card-header {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .card-body p {
            margin-bottom: 10px;
        }

        .card-body .product-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-body .product-images img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .address-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;

        }

        #mapid {
            height: 200px !important;
            width: 200px !important;
            border-radius: 20px !important;
        }
    </style>
</head>

<body>
    <button id="orders">Orders</button>
    <button id="address">Address</button>
    <button id="credit_cards">Cards</button>
    <button id="settings">Settings</button>
    <button id="logout">Logout</button>
    <div id="content">
        <!-- Initial content here -->
    </div>

</body>

</html>