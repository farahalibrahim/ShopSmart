<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
include_once('../check_login.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
if (!isset($_COOKIE['user_id'])) {
    header('Location: http://localhost:3000/PHP/login.php');
    exit;
}
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
    <style>
        .dashboard {
            display: flex;
        }

        #tickets-section {
            flex: 2;
        }
        .dashboard #tickets-section h2{
            color: #eee;
        }

        #chat-section {
            /* flex: 1; */
            /* border-left: #4C4C4C 1px solid; */
            height: 100vh;
            display: flex;
            flex-direction: column;
            width: 37px;
            transition: width 0.5s ease-in-out;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            /* Add a shadow */
            position: relative;
            /* Make it a relative container for the handle */
            border-radius: 20px;
            padding: 0 10px 0 10px;
            box-sizing: content-box;
        }

        #chat-section::before {
            content: "";
            position: absolute;
            left: 10px;
            /* Position it to the left of the chat section */
            top: 50%;
            transform: translateY(-50%);
            /* Center it vertically */
            width: 7px;
            height: 80px;
            background: rgba(128, 128, 128, 0.5);
            /* Gray with low opacity */
            border-radius: 10px;
            /* Rounded corners */
        }

        #chat-section.open {
            width: 33.33%;
        }

        .chat_content {
            flex-grow: 1;
            overflow-y: auto;
            height: 80%;
            max-height: 80%;
            /* Adjust this value as needed */
            padding: 0 0 0 27px;

        }

        #chat-header {
            height: 15%;
        }

        .chat_footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            height: 7%;
            width: 100%;
        }

        #chat_input {
            flex-grow: 1;
            margin-right: 10px;
            width: 80%;
        }

        #no-match,
        #no_open_ticket {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100%;
        }

        #ticket-search-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
            padding: 0.4em 0.8em;
            border-radius: 20px;
        }

        #ticket-search-input {
            flex-grow: 1;
            margin-left: 10px;
            margin-right: 10px;
        }

        #ticket-clear-search {
            background: none;
            border: none;
            cursor: pointer;
        }

        #ticket-clear-search>span.material-symbols-outlined {
            font-size: 12px;
            font-weight: bold;
            padding: 0;
        }
        #ticket-search-bar button{
            color: green;
        }
        

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-field {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-label {
            margin-right: 10px;
        }

        .form-input {
            border-radius: 20px;
            padding: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .submit-button {
            align-self: flex-end;
            margin-top: 10px;
        }

        .user-card {
            display: flex;
            justify-content: flex-start;
            padding: 20px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            position: relative;
        }

        /* 
        .user-card {
            display: flex;
            justify-content: flex-start;
        } */

        .user-icon {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .user-icon>* {
            font-size: 50px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 20px;
        }

        .user-info>* {
            margin: 0;
        }

        .user-actions {
            display: none;
            position: absolute;
            right: 20px;
            top: 0;
        }

        .user-card:hover .user-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        #freezeModal .modal-content textarea,
        #freezeModal .modal-content button {
            display: block;
            margin: auto;
        }

        #freezeModal .modal-content textarea {
            width: 100%;
            max-width: 100%;
            max-height: 100px;
            margin-bottom: 10px;
            overflow-y: auto;
        }

        #freezeModalSubmit:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        #freezeReason {
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Modal Close Button */
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

        .card:hover .card-body .delete-button {
            display: block;
        }

        header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            width: fit-content;
            border-radius: 20px;
            /* background-color: #eeeeee; */
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
        }

        header>* {
            margin-block: 0.5em;
        }

        #back_arrow {
            margin-inline: 0.1em;
            font-size: small;
            color: #4C4C4C;
        }

        #sortOrder {
            padding: 5px;
            border-radius: 20px;
            background-color: #f2f2f2;
            max-width: 10em;
            width: fit-content;
        }

        #order-search-bar {
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            background-color: #f2f2f2;
            width: 50%;
            max-width: 30em;
            margin-inline: 0.2em;
        }

        #order-search-input {
            flex-grow: 1;
            border: none;
            background-color: transparent;
        }

        #order-clear-search {
            margin-left: 5px;
            background-color: transparent;
        }


        #order-clear-search>span {
            font-size: 12px;
            color: #4C4C4C;
        }

        #filters {
            display: flex;
        }

        #filters>* {
            margin-left: 10px;
            justify-content: center;
            align-items: center;
        }

        .card {
            display: flex;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .card img {
            flex: 1;
            max-width: 100px;
            margin-right: 20px;
            object-fit: contain;
        }

        .card-body {
            flex: 2;
        }

        .card-body h3 {
            margin: 0 0 10px;
            font-size: 20px;
            max-width: 60%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            position: relative;
        }

        .card-body h3:hover {
            color: transparent;
        }

        .card-body h3:hover::after {
            content: attr(data-content);
            position: absolute;
            white-space: nowrap;
            background-color: #fff;
            padding: 0 5px;
            animation: slide 5s linear infinite;
            left: 100%;
            color: #000;
        }

        @keyframes slide {
            0% {
                left: 100%;
            }

            100% {
                left: -100%;
            }
        }

        .card-body p {
            /* margin: 0 0 10px; */
            margin: 0;
            font-size: smaller;
            color: #888;
        }

        /* .product_quantity, */
        .product_price {
            font-weight: bold;
            color: #4C4C4C;
            font-size: larger;
        }

        #add_product_popup {
            position: absolute;
            width: 200px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            z-index: 1;
        }

        #addModal,
        #productActionsModal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td {
            border: 1px solid black;
        }
        .products_section h2{
            color: green;
        }
        .products_section h2 span{
            color: gray;
        }
        .modal-content form h3{
            color: green;
        }
         .main-content #addModal .modal-content form table{
             border: none;
             
        }
        #product_search_type,#add_product_button,#sales_analytics, #analysis_duration{
            color: #eee;
            border: 1px solid;
            border-radius: 10px;
            padding: 10px;
            background: green;
        }
        .modal-content{
            border-radius: 20px;
        }
        #add_product_popup{
            margin-top: 10px;
        }
        .supermarket_analytics h2, .supermarket_managment h2{
            color: green;
        }
        .supermarket_analytics h2 span, .supermarket_managment h2 span {
            color: gray;
        }
        h1{
            color: #eee;
            border: 1px solid green;
            border-radius: 20px;
            padding: 15px;
            background: green;
        }
        .products_section #search_results .card .card-top {
            display: flex;
            width: 500px;
        }
        .products_section #search_results .card{
            display: grid;
            grid-template-columns: column;
        }
        .card-bottom .product_options summary{
            margin-bottom: 20px;
        }
        .product_options_table button span, .supermarket_table button span, .orders .card .card-body a span{
            border: 1px solid;
            border-radius: 20px;
            color: #eee;
            background: green;
            padding: 5px;
        }
        .product_options_table button, .supermarket_table button{
            border: none;
            background: none;
        }
         .product_section_top, .product_section_top #product_search_bar {
            display: flex;
        }
        .products_section #product_section_header{
            margin-right: 20px;
        }
        .products_section {
            display: grid;
        }
        #product_search_bar {
            margin-bottom: 20px;
            align-items: center;
            justify-content: flex-start;
            
        }
        #product_search_bar, #product_search_type{
            margin-right: 20px;
        }
        
        
        @media (max-width: 915px){
            .dashboard #chat-section {
                width: 90%;
                z-index: 10;
            }
        }

    </style>
    <script>
        $(document).ready(function() {
            $(document).on('mouseover', '.card-body h3', function() {
                if (!$(this).attr('data-content')) {
                    $(this).attr('data-content', $(this).text());
                }
            });
        });
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
                loadContent('dashboard/dashboard.php');
            });

            $('#users').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('users/users.php');
            });

            $('#orders').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('orders/orders.php');
            });

            $('#products').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('products/products.php');
            });

            $('#supermarkets').click(function() {
                $('a').removeClass('active');
                $(this).addClass('active');
                loadContent('supermarkets/supermarkets.php');
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
    <?php include_once '../responseModal.inc.php' ?>
    <?php include_once '../confirmationModal.inc.php' ?>
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
            <a href="#" id="dashboard" title="Dashboard"><span class="material-symbols-outlined" data-label="Dashboard">dashboard</span><span class="nav-text"> Dashboard</span></a>
            <a href="#" id="users" title="Users"><span class="material-symbols-outlined" data-label="Users">group</span><span class="nav-text"> Users</span></a>
            <!-- <a href="#" id="orders" title="Orders"><span class="material-symbols-outlined" data-label="Orders">shopping_cart</span><span class="nav-text"> Orders</span></a> -->
            <a href="#" id="products" title="Products"><span class="material-symbols-outlined" data-label="Products">inventory_2</span><span class="nav-text"> Products</span></a>
            <a href="#" id="supermarkets" title="Supermarkets"><span class="material-symbols-outlined" data-label="Supermarkets">store</span><span class="nav-text"> Supermarkets</span></a>
            <a href="#" id="logout" title="Logout"><span class="material-symbols-outlined" data-label="Logout">logout</span><span class="nav-text"> Logout</span></a>
        </nav>
    </div>
    <h1>Admin Portal</h1>
    <div id="main-content"></div>
    <!-- <p>Welcome to the admin portal. Here you can manage the products, orders, and supermarkets.</p> -->
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