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
    <a href="http://localhost:3000/HTML/test.php" class="logo"><span class="material-symbols-outlined">
            shopping_cart
        </span> Shop Smart</a>
    <div><span class="material-symbols-outlined" id="menuicon">
            menu
        </span></div>
    <nav class="navbar">
        <a href="http://localhost:3000/HTML/test.php#home">Home</a>
        <a href="http://localhost:3000/HTML/test.php#categories">Categories</a>
        <a href="http://localhost:3000/HTML/test.php#products">Products</a>
        <a href="http://localhost:3000/HTML/test.php#about">About</a>

    </nav>
    <!-- <div class="profile">
            <img src="../pics/pngwing.com.png" alt="">
            <span>Profile</span>
            <span class="material-symbols-outlined">
                arrow_drop_down
                </span>
        </div>-->
    <div class="profile">
        <a href="../HTML/login.html" target="_blank" id="login_btn">Login</a>
        <!-- user profile, cart, shopping list, inventory -->
        <span class="cart_account">
            <a href="#" id="shopping_list"><i class='bx bx-list-check'></i></a>
            <a href="http://localhost:3000/PHP/cart.php" id="cart"><i class='bx bx-cart-alt'></i></a>
            | <!-- seperator between account and the others icons -->
            <?php $userName = '';
            if (isset($_COOKIE['user_name'])) {
                $userName = $_COOKIE['user_name'];
                $userName = explode(' ', $userName)[0];
            }

            echo '<a href="#" id="account">' . $userName . '</a>'; ?>
            <a href="#" id="account"><i class='bx bx-user'></i></a></span>
    </div>
</header>