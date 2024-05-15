<?php
include_once '../connection.inc.php';
include_once '../dbh.class.inc.php';
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$user_id = $_COOKIE['user_id'];

$sql = "SELECT street_address, city FROM `user` WHERE id = :user";
$address = DatabaseHelper::runQuery($conn, $sql, ['user' => $user_id])->fetch(); ?>

<div class="header">
    <h2>Your Address</h2>
</div>

<div class="address-card">
    <?php
    include_once('../address_map.php');
    echo getAddressMap($address['street_address'], $address['city'], 'mapid');
    ?>
    <div class="address-card-body">
        <!-- <h5 class="card-title"><?php echo $address['street_address']; ?></h5> -->
        <p class="card-text"><?php echo $address['street_address'] . ", " . $address['city']; ?></p>
    </div>
    <button id="openModalButton"><span class="material-symbols-outlined">arrow_forward</span></button>
</div>
<!-- Modal to change address-->

<div id="addressModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <form id="addressForm">
            <label for="street_address">Street Address</label>
            <input type="text" id="street_address" name="street_address" value="<?php echo $address['street_address']; ?>"><br>
            <label for="city">City</label>
            <input type="text" id="city" name="city" value="<?php echo $address['city']; ?>"><br>
            <button type="submit">Save</button>
        </form>
    </div>
</div>
<script>
    // Modal script for modifying address
    $(document).ready(function() {
        // When the user clicks the button, open the modal 
        $('#openModalButton').click(function() {
            $('#addressModal').show();
        });

        // When the user clicks on <span> (x), close the modal
        $('#closeModalButton').click(function() {
            $('#addressModal').hide();
        });

        // When the user clicks anywhere outside of the modal, close it
        $(window).click(function(event) {
            if (event.target == document.getElementById('addressModal')) {
                $('#addressModal').hide();
            }
        });

        // When the user submits the form, send an AJAX request to the server
        $('#addressForm').submit(function(event) {
            event.preventDefault();

            var street_address = $('#street_address').val();
            var city = $('#city').val();

            $.post('save_address.php', {
                street_address: street_address,
                city: city
            }, function(data) {
                if (data.success) {
                    // Manually trigger click event on #address element to refresh
                    $('#address').click();
                    $('#addressModal').hide(); // Hide the modal
                } else {
                    alert('An error occurred');
                }
            }, 'json');
        });
    });
</script>