<head>
    <style>
        body{
            padding: 20px;
        }
        .card{
            font-size: 20px;
        }
        .card-title{
            color: Green;
            font-size: 25px;
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
    $sql = "SELECT * FROM `order` WHERE status = 'out_for_delivery' ORDER BY order_date ASC";
    $stmt = DatabaseHelper::runQuery($conn, $sql);

    if ($stmt->rowCount() == 0) {
        echo "<div class='no_packed'>";
        echo "<span class='material-symbols-outlined'>quick_reorder</span>";
        echo "<h2>No orders to deliver</h2>";
        echo "</div>";
    } else {
        while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sql = "SELECT * FROM `shipment` WHERE order_nb = :order_nb";
            $shipmentStmt = DatabaseHelper::runQuery($conn, $sql, ['order_nb' => $order['order_nb']]);

            while ($shipment = $shipmentStmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
                <div class="card" style="width: 18rem;">
                    <?php
                    include_once('../address_map.php');
                    echo getAddressMap($shipment['street_address'], $shipment['city'], 'mapid' . $shipment['order_nb']);
                    ?>
                    <div class="card-body">
                        <h5 class="card-title">Order# <?= $shipment['order_nb'] ?></h5>
                        <p class="card-text">
                            Payment Method: <?= $shipment['payment_method'] ?><br>
                            Status: <?= $shipment['status'] ?><br>
                            Address: <?= $shipment['street_address'] . ', ' . $shipment['city'] ?><br>
                            Phone: <?= $shipment['user_phone'] ?><br>
                            Payment Status: <?= $shipment['payment_status'] ?><br>
                            Payment Card: <?= $shipment['payment_card'] ?>
                        </p>
                        <button type="button" onclick="openPopup(<?= $shipment['order_nb'] ?>)">Upload Proof of Delivery</button>

                    </div>
                </div>
    <?php
            }
        }
    }
    ?>
</form>
<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form action="proof_of_delivery.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="order_nb" name="order_nb">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
            <input type="submit" value="Upload Image" name="submit">
        </form>
        <div id="uploadStatus"></div>
    </div>
</div>

<script>
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    function openPopup(order_nb) {
        document.getElementById('order_nb').value = order_nb;
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    var $uploadForm = $('form[action="proof_of_delivery.php"]');
    var $uploadStatus = $('#uploadStatus');

    $uploadForm.submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: $uploadForm.attr('action'),
            type: 'post',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(response) {
                $uploadStatus.text('Uploaded successfully');
                $('#shipment-' + $('#order_nb').val()).remove();
            },
            error: function() {
                $uploadStatus.text('Upload failed');
            }
        });
    });
</script>