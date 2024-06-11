<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
include_once('../responseModal.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- header and footer's css -->
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" /> <!-- <link rel="stylesheet" href="../CSS/popular_products.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Discount Coupons</title>
    <style>
        .coupon_container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .coupon_card {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            border: 1px dashed #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }

        .coupon_content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            flex: 2;
        }

        .coupon_barcode {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }

        .coupon_barcode img {
            transform: rotate(-90deg);
            width: 150px;
            padding-bottom: 20%;
        }

        .couponCode {
            font-size: 2em;
            /* adjust as needed */
            text-align: center;
        }

        .coupon_card h5 {
            font-size: 1.2em;
            /* adjust as needed */
            text-align: center;
            margin: 0;
        }

        .coupon_card p {
            text-align: center;
            margin: 0;
            margin-bottom: 20%;
        }

        .coupon_card:before,
        .coupon_card:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 20px;
            background: linear-gradient(transparent 50%, rgba(255, 255, 255, 0.5) 50%);
        }

        .coupon_card:before {
            top: 0;
        }

        .coupon_card:after {
            bottom: 0;
        }

        .coupon {
            display: flex;
            /* justify-content: space-between; */
            align-items: center;
        }

        .copy_coupon {
            background-color: transparent;
            /* color: white;
            padding: 10px 20px;
            margin: 8px 0;
            border: none; */
            cursor: pointer;
            /* border-radius: 5px; */
        }

        .copy_coupon>span {
            font-size: 14px;
        }

        /* .coupon_barcode>img {
            width: 100%;
            height: 100%;
        } */
    </style>
</head>

<body>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <?php include_once '../header.php'; ?>
    <?php
    $sql = "SELECT * FROM `coupon code`";
    $stmt = DatabaseHelper::runQuery($conn, $sql);
    $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php if (empty($coupons)) : ?>
        <div class="no_coupons" style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;">
            <span class="material-symbols-outlined" style="font-size: 40px;">
                confirmation_number
            </span>
            <h3 style="margin-bottom: 0;">No discount coupons available now</h3>
            <p>Try again later</p>
        </div>

    <?php else : ?>
        <div class="coupon_container">
            <?php foreach ($coupons as $coupon) : ?>
                <div class="coupon_card">
                    <div class="coupon_content">
                        <div class="coupon">
                            <h3 class="couponCode"><?php echo $coupon['coupon']; ?></h3>
                            <button class="copy_coupon" onclick="copyCoupon(event)"><span class="material-symbols-outlined">
                                    content_copy
                                </span></button>
                        </div>
                        <h5><?php echo $coupon['discount_percent'] . "%"; ?></h5>
                        <p>Expiry: <?php echo $coupon['coupon_expiry']; ?></p>
                    </div>
                    <div class="coupon_barcode">
                        <img src="http://localhost:3000/pics/barcode_vector.png" alt="">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>


        <?php include_once '../footer.php'; ?>
</body>

</html>

<script>
    function copyCoupon(event) {
        var couponCode = event.currentTarget.parentElement.getElementsByClassName('couponCode')[0].innerText;
        var textarea = document.createElement('textarea');
        console.log(couponCode);
        textarea.textContent = couponCode;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showResponseModal('Coupon code copied to clipboard');
    }
</script>