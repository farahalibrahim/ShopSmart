<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// for testing, assume that logged in user's email is: sed.neque@outlook.edu
// $email = 'sed.neque@outlook.edu';
$email = 'dolor@outlook.couk';
// $id = 1;


// // 
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



// // for password hashing and verification, to be moved to login/signup page after complete
// // hashing, PASSWORD_DEFAULT is constant
// $password = "userpassword";
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// // verification
// if (password_verify($password, $hashed_password)) {
//     echo 'Password is valid!';
// } else {
//     echo 'Invalid password.';
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Smart - Main Page</title>
    <!-- body css -->
    <link rel="stylesheet" href="../../CSS/index.css">
    <!-- header and footer's css -->
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" /> <!-- <link rel="stylesheet" href="../CSS/popular_products.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.logout').click(function() {
                $.post('../PHP/logout.php', function() {
                    window.location.href = 'http://localhost:3000/HTML/login.html'; // Redirect to the login page
                });
            });
        });
    </script>
    <style>
        .popular-container,
        .recent-container,
        .recommend-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .productcard {
            /* flex: 1 0 15%; */
            /* Grow and shrink, basis is 21% to allow for 5 items per row with some space in between */
            margin: 1%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            border-radius: 20px;
            width: 200px;
            height: 250px;
        }

        .productcard h2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .productcard img {
            width: 70%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .productcard:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include_once '../header.php'; ?>

    <!--Home Page-->
    <section class="home" id="home">
        <div class="swiper-wrapper">
            <div class="swiper-slide container ">
                <div class="home-text">
                    <span>Shop Smart</span>
                    <h1>Choose Your <br>best Offer <br>NOW !!!</h1>
                    <a href="../PHP/offers.php" class="btn">Shop Now <span class="material-symbols-outlined" id="R-arrow">
                            arrow_forward
                        </span></a>
                </div>
                <img src="../../pics/Best Offer 3D Text.G03.watermarked.2k.png" alt="">
            </div>

            <!--Slide 2-->
            <div class="swiper-slide container">
                <div class="home-text">
                    <span>Shop Smart</span>
                    <h1>Compare between <br>Many Offers <br> --><-- </h1>
                            <a href="#" class="btn">Shop Now <span class="material-symbols-outlined" id="R-arrow">
                                    arrow_forward
                                </span></a>
                </div>
                <img src="../../pics/Compare Symbol.G03.watermarked.2k.png" alt="">
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!--Categories Section-->
    <section class="categories" id="categories">
        <div class="heading">
            <h1>Browse Our <br><span>Categories</span></h1>
            <a href="#" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a>
        </div>
        <!--Categories Container-->
        <div class="categories-container">
            <?php
            $sql = "SELECT DISTINCT category FROM product";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as $category) {
                $categoryName = $category['category'];

                $sql = "SELECT COUNT(*) FROM product WHERE category = :category";
                $category_stmt = DatabaseHelper::runQuery($conn, $sql, array(":category" => $categoryName));
                $result = $category_stmt->fetch(PDO::FETCH_ASSOC);
                $count = $result['COUNT(*)'];
            ?>
                <div class="box box1">
                    <img src="../../pics/<?php echo $categoryName; ?>.png" alt="">
                    <h2><?php echo $categoryName; ?></h2>
                    <span><?php echo $count; ?> Items</span>
                    <a href="../products/products.php?category=<?php echo str_replace(' & ', '_', $categoryName); ?>"><span class="material-symbols-outlined" id="catarrow">
                            arrow_forward
                        </span></a>
                </div>
            <?php
            }
            ?>
        </div>

    </section>

    <!--Products Section-->

    <section class="products" id="products">
        <div class="heading">
            <h1>Our Popular <br><span>Products</span></h1>
            <a href="../popular_products.php" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a>
        </div>
        <!--Products Container-->
        <div class="popular-container">
            <?php include_once("get_popular_products.php");
            echo get_popular_products($conn, 8);
            ?>
        </div>
    </section>

    <?php if (isset($_COOKIE['user_id'])) : ?> <!-- Displayed only if user logged in -->
        <!--Buy Again-->
        <section class="products" id="products">
            <div class="heading">
                <h1>Buy <br><span>Again</span></h1>
                <!-- <a href="../PHP/popular_products.php" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a> -->
            </div>
            <!--Products Container-->
            <div class="recent-container">
                <?php include_once("buy_again.php");
                echo get_recent_products($conn, 8, $user_id);
                ?>
            </div>
        </section>

        <!--You May Also Like-->
        <section class="products" id="products">
            <div class="heading">
                <h1>You May Also <br><span>Like</span></h1>
                <!-- <a href="../PHP/popular_products.php" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a> -->
            </div>
            <!--Products Container-->
            <div class="recommend-container">
                <?php include_once("recommended_products.php");
                echo $products;
                ?>
            </div>
        </section>
    <?php endif; ?>

    <!--Footer Section-->
    <?php include_once '../footer.php'; ?>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!--link to js-->
    <script src="../../JS/index.js">

    </script>
</body>

</html>