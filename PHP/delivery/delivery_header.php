<head>
    <style>
        .navbar{
            cursor: pointer;
        }
    </style>
</head>
<?php
if (isset($_COOKIE['user_id'])) {
    // User ID cookie exists, hide login button
    echo '<style>.profile #login_btn{ display: none; }
    .profile .cart_account{ display: block; }</style>';
    // echo '<style>.profile { display: none; }</style>';
} else {
    // User ID cookie does not exist, make login button visible
    echo '<style>.profile #login_btn{ display: block; }
    .profile .cart_account{ display: none; }</style>';
    // echo '<style>.profile{ display: block; }</style>';
}
?>

<header class="header">
    <a href="http://localhost:3000/PHP/delivery/delivery.php" class="logo"><span class="material-symbols-outlined">
            shopping_cart
        </span> Shop Smart</a>
    <div><span class="material-symbols-outlined" id="menuicon">
            menu
        </span></div>
    <nav class="navbar">
        <!-- <a id="packed_orders" href="http://localhost:3000/HTML/test.php#home">All Shipments</a>
        <a id="out_for_delivery_orders" href="http://localhost:3000/HTML/test.php#categories">Deliver</a> -->
        <a id="packed_orders">All Shipments</a>
        <a id="out_for_delivery_orders">Deliver</a>
    </nav>
    <script>
        $(document).ready(function() {
            $("#packed_orders").click(function() {
                $("#content").load("packed_orders.php");
            });
            $("#out_for_delivery_orders").click(function() {
                $("#content").load("out_for_delivery_orders.php");
            });
        });
    </script>
    <!-- <div class="profile">
            <img src="../pics/pngwing.com.png" alt="">
            <span>Profile</span>
            <span class="material-symbols-outlined">
                arrow_drop_down
                </span>
        </div>-->
    <div class="user">
        <!-- <a href="../HTML/login.html" target="_blank" id="login_btn">Login</a> -->
        <span class="cart_account">
            <?php $userName = '';
            if (isset($_COOKIE['user_name'])) {
                $userName = $_COOKIE['user_name'];
                $userName = explode(' ', $userName)[0];
            }

            echo '<a href="#" id="account">' . $userName . '</a>'; ?>

            <button class="logout"><span class="material-symbols-outlined">logout</span></button>
    </div>
</header>
<script>
    $(document).ready(function() {
        $('.logout').click(function() {
            $.post('../logout.php', function() {
                window.location.href = 'http://localhost:3000/PHP/login.php'; // Redirect to the login page
            });
        });
    });
</script>