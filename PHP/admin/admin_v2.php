<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);


$sql = "SELECT * FROM user WHERE id = :id";
$stmt = DatabaseHelper::runQuery($conn, $sql, array(":id" => $_COOKIE['user_id']));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
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

        .sidebar {
            display: flex;
            flex-direction: column;
            justify-content: start;
            width: 300px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #eee;
            color: green;
            transition: width 0.3s;
            box-shadow: 0 8px 11px rgb(15 55 54 / 15%);
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
            margin-left: 315px;
            transition: margin-left 0.3s;
        }

        body.retracted {
            margin-left: 85px;
            transition: margin-left 0.3s;
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
            font-size: 20px;
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
            font-size: larger;
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
    </style>
    <script>
        $(document).ready(function() {
            // Function to load content via AJAX
            function loadContent(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#main-content').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

            // Event handlers for button clicks
            $('#dashboard').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('get_user_orders.php');
            });

            $('#orders').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('address.php');
            });

            $('#products').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('credit_cards.php');
            });

            $('#supermarkets').click(function() {
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
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="top-section">
            <a href="http://localhost:3000/PHP/main/index.php" class="logo">
                <span class="material-symbols-outlined" data-label="Shopping Cart">shopping_cart</span>
                <h3 class="nav-text">Shop Smart</h3>
            </a>
            <button onclick="toggleSidebar()" id="toggle-button">
                <span class="material-symbols-outlined" id="toggle-icon" data-label="Menu">menu_open</span>
            </button>
        </div>
        <div class="user">
            <span class="material-symbols-outlined" data-label="Account">account_circle</span>
            <div class="nav-text">
                <h4><?= $_COOKIE['user_name'] ?></h4>
                <p><?= $user['role'] ?></p>
            </div>
        </div>
        <nav>
            <a href="#" id="dashboard"><span class="material-symbols-outlined" data-label="Dashboard">dashboard</span><span class="nav-text"> Dashboard</span></a>
            <a href="#" id="orders"><span class="material-symbols-outlined" data-label="Orders">shopping_cart</span><span class="nav-text"> Orders</span></a>
            <a href="#" id="products"><span class="material-symbols-outlined" data-label="Products">inventory_2</span><span class="nav-text"> Products</span></a>
            <a href="#" id="supermarkets"><span class="material-symbols-outlined" data-label="Supermarkets">store</span><span class="nav-text"> Supermarkets</span></a>
            <a href="#" id="logout"><span class="material-symbols-outlined" data-label="Logout">logout</span><span class="nav-text"> Logout</span></a>
        </nav>
    </div>
    <div class="main-content">
        <h1>Admin Portal</h1>
        <p>Welcome to the admin portal. Here you can manage the products, orders, and supermarkets.</p>
        <script>
            $(document).ready(function() {
                $('#dashboard').click();
            });
        </script>
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
</body>

</html>