<?php
if (isset($_COOKIE['user_id'])) {
    // User ID cookie exists, hide login button
    echo '<style>.profile #login_btn{ display: none; }
    .profile .cart_account{ display: inline-flex; }</style>';
    // echo '<style>.profile { display: none; }</style>';
} else {
    // User ID cookie does not exist, make login button visible
    echo '<style>.profile #login_btn{ display: block; }
    .profile .cart_account{ display: none; }</style>';
    // echo '<style>.profile{ display: block; }</style>';
}
?>

<header class="header">
    <a id="back_arrow" href="#" onclick="event.preventDefault(); goBack();"><span class="material-symbols-outlined">
            arrow_back
        </span></a>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <script src="https://kit.fontawesome.com/f1d5e2b530.js" crossorigin="anonymous"></script>
    <a href="http://localhost:3000/PHP/main/index.php" class="logo"><span class="material-symbols-outlined">
            shopping_cart
        </span> Shop Smart</a>
    <div><span class="material-symbols-outlined" id="menuicon">
            menu
        </span></div>
    <nav class="navbar">
        <a href="http://localhost:3000/PHP/main/index.php#home">Home</a>
        <a href="http://localhost:3000/PHP/main/index.php#categories">Categories</a>
        <a href="http://localhost:3000/PHP/main/index.php#products">Products</a>
        <!--<a href="http://localhost:3000/PHP/main/index.php#about">About</a>-->

    </nav>

    <div class="search-container">
        <div class="search">
            <span class="material-symbols-outlined" id="search_icon">search</span>
            <input id="search_input" type="text" placeholder="search">
        </div>
        <div class="search-results" id="search_results">
            <!-- Search results will be populated here -->
        </div>
    </div>
    <script>
        document.addEventListener('click', function(event) {
            var search = document.getElementById('search');
            var input = document.getElementById('search_input');
            var results = document.getElementById('search_results');
            if (!search.contains(event.target)) {
                results.style.display = 'none';
            }
        });
        $(document).ready(function() {
            $('#search_input').on('input', function() {
                var searchQuery = $(this).val();
                if (searchQuery === '') {
                    $('#search_results').hide();
                } else {
                    $.ajax({
                        url: 'http://localhost:3000/PHP/search.php',
                        type: 'POST',
                        data: {
                            query: searchQuery
                        },
                        success: function(data) {
                            if (data) {
                                $('#search_results').html(data).show();
                            } else {
                                $('#search_results').hide();
                            }
                        }
                    });
                }
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
    <div class="profile">
        <a href="../login.php" target="_blank" id="login_btn">Login</a>
        <!-- user profile, cart, shopping list, inventory -->
        <!-- <button class="logout"><span class="material-symbols-outlined">logout</span></button>
        <script>
            $(document).ready(function() {
                $('.logout').click(function() {
                    $.post('../logout.php', function() {
                        window.location.href = 'http://localhost:3000/PHP/login.php'; // Redirect to the login page
                    });
                });
            });
        </script> -->
        <span class="cart_account">
            <a href="http://localhost:3000/PHP/shopping_list/shoppinglist.php" id="shopping_list"><i class='bx bx-list-check'></i></a>
            <a href="http://localhost:3000/PHP/cart_checkout/cart.php" id="cart"><i class='bx bx-cart-alt'></i></a>

            <?php $userName = '';
            if (isset($_COOKIE['user_id'])) {
                $query = "SELECT name FROM user WHERE id = {$_COOKIE['user_id']}";
                $stmt = DatabaseHelper::runQuery($conn, $query);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $userName = explode(' ', $result['name'])[0]; // Get the first name
            } ?>

            <a href="http://localhost:3000/PHP/profile/profile.php" id="account" style="display: flex; flex-direction:row; align-items:center; justify-content:space-between">|<span style="padding-inline: 10%;"> <?= $userName ?></span><i class='bx bx-user'></i> </a>
            <!-- <a href="http://localhost:3000/PHP/profile/profile.php" id="account"></a> -->
        </span>


</header>