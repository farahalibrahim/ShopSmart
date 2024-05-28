<head>
    <style>
        form{
            padding: 20px;
        }
        .card{
            font-size: 20px;
            margin-bottom: 20px;
        }
        .card-title{
            color: Green;
            font-size: 25px;
        }
        form input{
            color: #eee;
            border: 1px solid;
            border-radius: 10px;
            background: green;
            padding: 10px 20px;

        }
        .card-body{
            display: flex;
        }
        .card-text{
            margin-left: 20px;
        }
        
    </style>
</head>
<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
?>

<form method="POST" action="update_delivery_status.php">
    <?php
    $sql = "SELECT * FROM `order` WHERE status = 'packed' ORDER BY order_date ASC";
    $stmt = DatabaseHelper::runQuery($conn, $sql);

    if ($stmt->rowCount() == 0) {
        echo "<div class='no_packed'>";
        echo "<span class='material-symbols-outlined'>orders</span>";
        echo "<h2>No packed orders</h2>";
        echo "</div>";
    } else {
        while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sql = "SELECT * FROM `shipment` WHERE order_nb = :order_nb";
            $shipmentStmt = DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order['order_nb']]);

            while ($shipment = $shipmentStmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
                <div class="card" style="width: 18rem;">
                <h5 class="card-title">Order# <?= $shipment['order_nb'] ?></h5>
                    <div class="card-body">
                    <input  type="checkbox" name="orders[]" value="<?= $shipment['order_nb'] ?>">
                        <p class="card-text">
                            Payment Method: <?= $shipment['payment_method'] ?><br>
                            Status: <?= $shipment['status'] ?><br>
                            Address: <?= $shipment['street_address'] . ', ' . $shipment['city'] ?><br>
                            Phone: <?= $shipment['user_phone'] ?><br>
                            Payment Status: <?= $shipment['payment_status'] ?><br>
                            <?php if ($shipment['payment_method'] == "card") {
                                echo "Payment Card: {$shipment['payment_card']}";
                            } ?>
                        </p>
                        
                    </div>
                </div>
    <?php
            }
        }
        echo '<input id="update_status" type="submit" value="Update Status">';
    }
    ?>
</form>