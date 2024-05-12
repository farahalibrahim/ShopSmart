<?php
include_once('../PHP/connection.inc.php');
include_once('../PHP/dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Offers</title>
    <link rel="stylesheet" href="../CSS/header_footer.css">
    <link rel="stylesheet" href="../CSS/offercards.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <?php //include_once '../PHP/header.php'; 
    ?>
    <div class="container">
        <?php include_once '../PHP/getofferitems.php'; ?>

    </div>
    <?php include_once '../PHP/footer.php'; ?>
    <script src="../JS/addtocart.js"></script>
</body>

</html>