<?php
include_once('../PHP/connection.inc.php');
include_once('../PHP/dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// for testing, assume that logged in user's email is: sed.neque@outlook.edu
$email = 'sed.neque@outlook.edu';
// $id = 1;



$sql = "SELECT id,name FROM user WHERE email = :email";
$stmt = DatabaseHelper::runQuery($conn, $sql, ['email' => $email]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $row['id'];
$user_name = $row['name'];

setcookie('user_id', $user_id, time() + 86400, '/');
setcookie('user_name', $user_name, time() + 86400, '/');
// time() + 86400, '/' : current unix timestamp + 24hours. '/' states that cookie is accessable thogh all the domain

// // for testing, delete cookies
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
    <meta name="viewport" content="width=s, initial-scale=1.0">
    <title>Document</title>
    <!-- body css -->
    <link rel="stylesheet" href="../CSS/test.css">
    <!-- header and footer's css -->
    <link rel="stylesheet" href="../CSS/header_footer.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <!-- header -->
    <?php include_once '../PHP/header.php'; ?>

    <!--Home Page-->
    <section class="home" id="home">
        <div class="swiper-wrapper">
            <div class="swiper-slide container ">
                <div class="home-text">
                    <span>Shop Smart</span>
                    <h1>Choose Your <br>best Offer <br>NOW !!!</h1>
                    <a href="#" class="btn">Shop Now <span class="material-symbols-outlined" id="R-arrow">
                            arrow_forward
                        </span></a>
                </div>
                <img src="../pics/Best Offer 3D Text.G03.watermarked.2k.png" alt="">
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
                <img src="../pics/Compare Symbol.G03.watermarked.2k.png" alt="">
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
                    <img src="../pics/<?php echo $categoryName; ?>.png" alt="">
                    <h2><?php echo $categoryName; ?></h2>
                    <span><?php echo $count; ?> Items</span>
                    <a href="../PHP/products.php?category=<?php echo str_replace(' & ', '_', $categoryName); ?>"><span class="material-symbols-outlined" id="catarrow">
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
            <a href="#" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a>
        </div>
    </section>

    <!--Footer Section-->
    <?php include_once '../PHP/footer.php'; ?>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!--link to js-->
    <script src="../JS/test.js">

    </script>
</body>

</html>