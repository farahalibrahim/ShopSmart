<head>
    <style>
        body {
            padding: 20px;
        }

        .card {
            font-size: 20px;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            border-radius: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            padding-inline: 10px;
        }

        .card-title {
            color: Green;
            font-size: 25px;
            margin-bottom: 5%;
        }

        .card-text {
            font-size: large;
        }

        .card-details {
            margin-left: 5%;
        }

        .modal-content {
            border-radius: 20px;
            width: 80%;
            max-width: 700px;
        }

        #myModal {
            font-size: 20px;
            font-weight: bold;
            z-index: 1000;
        }

        .card-body {
            font-size: 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .card-body button {
            color: #eee;
            border: 1px solid;
            border-radius: 10px;
            background: green;
            padding: 10px;
            height: fit-content;

        }

        .card-body button:hover {
            background: #eee;
            color: green;
            cursor: pointer;
        }

        .modal .modal-content form input {
            color: #eee;
            border: 1px solid;
            border-radius: 10px;
            background: green;
            padding: 10px 20px;
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
                <div class="card" style="/*width: 18rem;*/">
                    <?php
                    include_once('../address_map.php');
                    echo getAddressMap($shipment['street_address'], $shipment['city'], 'mapid' . $shipment['order_nb']);
                    ?>
                    <div class="card-body">
                        <div class="card-details">
                            <h5 class="card-title">Order# <?= $shipment['order_nb'] ?></h5>
                            <p class="card-text">
                                Payment Method: <?= $shipment['payment_method'] ?><br>
                                Status: <?= $shipment['status'] ?><br>
                                Address: <?= $shipment['street_address'] . ', ' . $shipment['city'] ?><br>
                                Phone: <?= $shipment['user_phone'] ?><br>
                                Payment Status: <?= $shipment['payment_status'] ?><br>
                                Payment Card: <?= $shipment['payment_card'] ?>
                            </p>
                        </div>
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
        <div id="uploadStatus" style="font-size: medium;font-weight: normal;"></div>
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