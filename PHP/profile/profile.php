<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = $_COOKIE['user_id'];

$sql = "SELECT * FROM user WHERE id = :id";
$stmt = DatabaseHelper::runQuery($conn, $sql, array(":id" => $_COOKIE['user_id']));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= explode(' ',  $user['name'])[0] . "'s Profile" ?></title>
    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Boxsymbols -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxsymbols@latest/css/boxsymbols.min.css">
    <!-- Google Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- OpenStreetMap API -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section');

            if (section === 'cards') { // from checkout no cards add button
                $('#credit_cards').click();
            } else if (section === 'address') { // from checkout address change href
                $('#address').click();
            }
            //  else if (section === 'settings') {
            //     $('#settings').click();
            // } else {
            //     $('#orders').click();
            // }
        }
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
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('get_user_orders.php');
            });

            $('#address').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('address.php');
            });

            $('#credit_cards').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('credit_cards.php');
            });

            $('#settings').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
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
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            /* margin: 0;
            padding: 0; */
            box-sizing: border-box;
            outline: none;
            border: none;
            text-decoration: none;
            /* text-transform: capitalize; */
            transition: all .2s linear;
        }

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

        .add_button,
        .save_button {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        button:hover {
            border: green 2px solid;
            background-color: white;
            color: black;
        }

        .delete_account {
            background-color: red;
        }

        .delete_account:hover {
            background-color: white;
            color: red;
            border: 2px solid red;
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

        button>*:last-child {
            margin-right: 0;
        }

        button>* {
            margin-right: 10px;
        }

        .form_header,
        .modal_header {
            margin: 20px auto;
            margin-bottom: 30px;
            /* Center the element horizontally */
            display: flex;
            justify-content: center;
            align-items: center;
            /* Center the element vertically */
        }

        .pass_visibility {
            cursor: pointer;
            background-color: transparent;
            color: black;
            margin: 0%;
            padding: 0%;
        }

        .pass_visibility span {
            font-size: 20px;
            color: #ccc;
        }

        .pass_visibility:hover {
            border: none;
        }

        #userModal>.modal-content,
        #addressModal>.modal-content,
        #verificationModal>.modal-content,
        #addCardModal>.modal-content {
            width: 80%;
            padding: 20px 50px;
            box-sizing: border-box;
            border-radius: 30px;
        }

        .oldPasswordDiv,
        .repeatOldPasswordDiv,
        .newPasswordDiv {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }

        .oldPasswordDiv label,
        .repeatOldPasswordDiv label,
        .newPasswordDiv label {
            flex: 1;
            text-align: justify;
            margin-right: 10px;
            /* Add space between the label and the input */
        }

        .oldPasswordDiv input,
        .repeatOldPasswordDiv input,
        .newPasswordDiv input {
            flex-grow: 1;
            /* Make the input take up the remaining space */
            height: 40px;
            /* Make the input higher */
            border: none;
            /* Remove the border */
            border-radius: 20px;
            /* Add round corners */
            outline: none;
            /* Remove the default outline */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            /* Add a box shadow */
            padding-right: 50px;
            padding: 0 20px;
            /* Make room for the button */
            position: relative;
            /* Needed to position the button inside the input */
        }

        .oldPasswordDiv button,
        .repeatOldPasswordDiv button,
        .newPasswordDiv button {
            position: absolute;
            /* Position the button inside the input */
            right: 10px;
            /* Position the button from the right */
            top: 50%;
            /* Position the button from the top */
            transform: translateY(-50%);
            /* Center the button vertically */
        }

        .inputDiv {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .inputDiv label {
            flex: 1;
            margin-right: 10px;
            text-align: justify;
        }

        .inputDiv input {
            flex-grow: 1;
            height: 40px;
            border: none;
            border-radius: 20px;
            outline: none;
            padding: 0 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .settings-card>div:not(:first-child) {
            /* skip .name */
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        /* .name h3, */
        .email h4,
        .pass h4,
        .phone h4 {
            margin-right: 10px;
        }

        .name span {
            font-size: 12px;
        }

        button {
            margin-left: auto;
            /* Push the button to the far right */
        }

        .email {
            margin-top: 20px;
        }

        .name h3 {
            margin-block: 0;
        }

        .name span {
            cursor: pointer;
        }

        .name {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            margin-top: 30px;
            /* Align items at the top */
        }

        /* 
        .name h3,
        .name span {
            margin-right: 10px;
        } */
    </style>
    <!-- sidebar styling -->
    <style>
        .sidebar {
            display: flex;
            flex-direction: column;
            justify-content: start;
            width: 230px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #eee;
            color: green;
            transition: width 0.3s;
            box-shadow: 0 11px 8px rgb(15 55 54 / 15%);
            overflow: auto;
        }

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .top-section>button {
            border: 1px solid black;
        }

        .top-section #toggle-button {
            margin-right: auto;
            flex: 1;
            /* border: 1px solid black; */
        }

        #toggle-button #toggle-icon {
            margin-right: 0;
        }

        .top-section .logo {
            flex: 4;
        }

        .user span.material-symbols-outlined {
            font-size: 35px;
        }

        .sidebar.retracted .user span.material-symbols-outlined {
            font-size: 24px;
        }

        .nav {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* .sidebar nav a, */
        .sidebar .logo,
        .sidebar .user,
        .sidebar #toggle-button {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            padding-inline-start: 20px;
        }

        .sidebar.retracted {
            width: 75px;
        }

        .sidebar.retracted nav a,
        /* .sidebar.retracted .logo, */
        .sidebar.retracted .user,
        .sidebar.retracted #toggle-button {
            justify-content: center;
            padding-inline-start: 0;
        }

        .sidebar.retracted nav a span.material-symbols-outlined,
        .sidebar.retracted .user span.material-symbols-outlined {
            margin-right: 0;
        }


        .sidebar.retracted .nav-text {
            display: none;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            padding: 20px;
            color: white;
            text-decoration: none;
            overflow: hidden;
            width: 93%;
            padding-right: 0;
        }

        .sidebar .nav-text {
            color: #4C4C4C;
        }

        .sidebar nav a span.material-symbols-outlined,
        .sidebar .top-section .logo span.material-symbols-outlined,
        .sidebar .top-section button span.material-symbols-outlined,
        .sidebar .user span.material-symbols-outlined {
            margin-right: 10px;
            color: green;
        }

        body {
            margin-left: 260px;
            transition: margin-left 0.3s;
        }

        body.retracted {
            margin-left: 105px;
            transition: margin-left 0.3s;
        }

        .sidebar.retracted #toggle-button {
            padding-right: 0;
        }

        #toggle-button {
            background: none;
            border: none;
            cursor: pointer;
            width: fit-content;
        }

        .sidebar.retracted .logo {
            display: none;
        }

        .sidebar.retracted .top-section {
            margin-top: 20px;
            /* Increase this value as needed */
        }

        .sidebar .user,
        .sidebar nav a:first-child {
            margin-top: 30px;
            /* Increase this value as needed */
        }

        .sidebar nav a:not(:first-child) {
            margin-top: 10px;
            /* Increase this value as needed */
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: green;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
            border-radius: 30px;
            font-size: 16px;
            transition: font-size 0.3s ease-in-out;
            /* transition: background-color 0.3s ease-in-out; */
        }

        .sidebar nav a:hover span.material-symbols-outlined,
        .sidebar nav a.active span.material-symbols-outlined {
            font-size: 28px;
            transition: font-size 0.3s ease-in-out;
            /* transition: background-color 0.3s ease-in-out; */
        }

        .sidebar nav a:hover span.material-symbols-outlined,
        .sidebar nav a:hover .nav-text,
        .sidebar nav a.active span.material-symbols-outlined,
        .sidebar nav a.active .nav-text {
            color: white;
        }

        .sidebar nav a:hover .nav-text,
        .sidebar nav a.active .nav-text {
            font-size: 14px;
            font-weight: bold;
        }

        span#toggle-icon:hover,
        a.active span#toggle-icon {
            font-size: 30px;
            transition: font-size 0.3s ease-in-out;
        }


        .user .nav-text>* {
            margin: 0;
            padding: 0%;
        }

        .user {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .no-card,
        .no-order {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* Center the elements on the same x-axis */
        }

        .no-card span.material-symbols-outlined,
        .no-order span.material-symbols-outlined {
            font-size: 50px;
            /* Make the span large */
        }
    </style>
</head>

<body>
    <!-- <button id="orders">Orders</button>
    <button id="address">Address</button>
    <button id="credit_cards">Cards</button>
    <button id="settings">Settings</button>
    <button id="logout">Logout</button> -->
    <div class="sidebar" id="sidebar">
        <div class="top-section">
            <a href="http://localhost:3000/PHP/main/index.php" class="logo">
                <span class="material-symbols-outlined" data-label="Shopping Cart">shopping_cart</span>
                <h5 class="nav-text">Shop Smart</h5>
            </a>
            <button onclick="toggleSidebar()" id="toggle-button">
                <span class="material-symbols-outlined" id="toggle-icon" data-label="Menu">menu_open</span>
            </button>
        </div>
        <div class="user">
            <span class="material-symbols-outlined" data-label="Account">account_circle</span>
            <div class="nav-text">
                <h5><?= $_COOKIE['user_name'] ?></h5>
                <?php if ($user['role'] !== 'user') : ?>
                    <p><?= $user['role'] ?></p>
                <?php endif; ?>
            </div>
        </div>
        <nav>
            <a href="#" id="orders"><span class="material-symbols-outlined">list_alt</span><span class="nav-text"> Orders</span></a>
            <a href="#" id="address"><span class="material-symbols-outlined">home</span><span class="nav-text"> Address </span></a>
            <a href="#" id="credit_cards"><span class="material-symbols-outlined">credit_card</span><span class="nav-text"> Credit Cards</span></a>
            <a href="#" id="settings"><span class="material-symbols-outlined">settings</span><span class="nav-text"> Settings</span></a>
            <a href="#" id="logout"><span class="material-symbols-outlined">logout</span><span class="nav-text"> Logout</span></a>
        </nav>
    </div>
    <div id="content">
        <!-- Initial content -->
        <script>
            $(document).ready(function() {
                $('#settings').click();
            });
        </script>
    </div>

</body>

</html>
<script>
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        var menu_icon = document.getElementById('toggle-icon');
        var body = document.body;
        sidebar.classList.toggle('retracted');
        body.classList.toggle('retracted');
        menu_icon.innerHTML = sidebar.classList.contains('retracted') ? 'menu' : 'menu_open';
    }
</script>