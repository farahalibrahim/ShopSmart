<!--Box 1-->
<div class="box box1">
    <img src="../pics/Fruits & Vegetables.png" alt="">
    <h2>Fruits & Vegetables</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Fruits & Vegetables'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Fruits_Vegetables"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>

<!--Box 2-->
<div class="box box1">
    <img src="../pics/Bakery.png" alt="">
    <h2>Bakery</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Bakery'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Bakery"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>

<!--Box 3-->
<div class="box box1">
    <img src="../pics/Dairy.png" alt="">
    <h2>Dairy</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Dairy'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Dairy"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>

<!--Box 4-->
<div class="box box1">
    <img src="../pics/Meat & Chicken.png" alt="">
    <h2>Meat & Chicken</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Meat & Chicken'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Meat_Chicken"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>

<!--Box 5-->
<div class="box box1">
    <img src="../pics/Snacks.png" alt="">
    <h2>Snacks</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Snacks'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Snacks"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>

<!--Box 6-->
<div class="box box1">
    <img src="../pics/Beverages.png" alt="">
    <h2>Baverages</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Baverages'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Beverages"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>

<!--Box 7-->
<div class="box box1">
    <img src="../pics/Personal Care.png" alt="">
    <h2>Personal Care</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Personal Care'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Personal Care"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>

<!--Box 8-->
<div class="box box1">
    <img src="../pics/Cleaning Supplies.png" alt="">
    <h2>Cleaning Supplies</h2>
    <span><?php
            $sql = "SELECT COUNT(*) FROM product WHERE category = 'Cleaning Supplies'";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $result['COUNT(*)'];

            ?> Items</span>
    <a href="../PHP/products.php?category=Cleaning Supplies"><span class="material-symbols-outlined" id="catarrow">
            arrow_forward
        </span></a>
</div>