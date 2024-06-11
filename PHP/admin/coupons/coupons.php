<div id="addCouponModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="addCouponForm">
            <h3 class="form_header">Add Coupon</h3>
            <span id="add_coupon_status" style="display: none; border: red 1px solid; background-color: rgba(255, 0, 0, 0.2);
    text-align: center;
    color: red;
    padding-block: 10px;
    justify-content: center;
    align-items: center; "></span>
            <table>
                <tr>
                    <td>
                        <h5>Coupon:</h5>
                    </td>
                    <td><input type="text" id="coupon" name="coupon" class="form-input"></td>
                </tr>
                <tr>
                    <td>
                        <h5>Discount Percent:</h5>
                    </td>
                    <td><input type="number" id="discount_percent" name="discount_percent" class="form-input" min="1" max="100" step="1"></td>
                </tr>
                <tr>
                    <td>
                        <h5>Expiry:</h5>
                    </td>
                    <td><input type="date" id="coupon_expiry" name="coupon_expiry" class="form-input" min="<?php echo date("Y-m-d"); ?>"></td>
                </tr>
            </table>
            <button type="submit" id="add_coupon"><span class="material-symbols-outlined">save</span><span>Save</span></button>

        </form>
    </div>
</div>
<div id="editCouponModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="editCouponForm">
            <h3 class="form_header">Edit Coupon</h3>
            <span id="edit_coupon_status" style="display: none; border: red 1px solid; background-color: rgba(255, 0, 0, 0.2);
    text-align: center;
    color: red;
    padding-block: 10px;
    justify-content: center;
    align-items: center; "></span>
            <table>
                <tr>
                    <td>
                        <h5>Coupon:</h5>
                    </td>

                    <td><span id="edit_coupon"></span></td>
                </tr>
                <tr>
                    <td>
                        <h5>Discount Percent:</h5>
                    </td>
                    <td><input type="number" id="edit_discount_percent" name="edit_discount_percent" class="form-input" min="1" max="100" step="1"></td>
                </tr>
                <tr>
                    <td>
                        <h5>Expiry:</h5>
                    </td>
                    <td><input type="date" id="edit_coupon_expiry" name="edit_coupon_expiry" class="form-input" min="<?php echo date("Y-m-d"); ?>"></td>
                </tr>
            </table>
            <button type="submit" id="edit_coupon_button"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
    </div>
</div>
<h2 class="coupons_header"><span>Manage</span> Coupons</h2>
<div class="top_section">
    <div id="coupons-search-bar">
        <select id="coupons-search-type">
            <option value="coupon">Coupon</option>
            <option value="discount_percent">Discount percent</option>
        </select>
        <div class="search_field">
            <input type="text" id="coupons-search-input" placeholder="Search..."> <button id="user-clear-search"><span class='material-symbols-outlined'>close</span></button>
        </div>
    </div>
    <button id="addCoupon"><span class="material-symbols-outlined">add</span><span>Add</span></button>
</div>
<div class="coupons_section">
    <!-- content added dynamically -->
</div>

<script>
    // search coupons
    $(document).ready(function() {
        $('#coupons-search-type').change(function() {
            $('#coupons-search-input').trigger('input');
        });
        $('#coupons-search-input').on('input', function() {
            var searchType = $('#coupons-search-type').val();
            var searchValue = $(this).val();
            console.log(searchType, searchValue);

            $.ajax({
                url: 'coupons/get_coupons.php',
                type: 'POST',
                data: {
                    searchType: searchType,
                    searchValue: searchValue
                },
                success: function(response) {
                    $('.coupons_section').html(response);
                }
            });
        });
        // show all available coupons initially
        $('#coupons-search-input').trigger('input');
    });

    // add coupon
    $(document).ready(function() {
        $('#addCoupon').click(function() {
            $('#addCouponModal').show();
        });

        $('.close').click(function() {
            $('#addCouponModal').hide();
        });

        $('#addCouponModal').click(function(event) {
            if (event.target == this) {
                $('#addCouponModal').hide();
            }
        });

        $('#add_coupon').click(function(e) {
            e.preventDefault();

            var coupon = $('#coupon').val();
            var discountPercent = $('#discount_percent').val();
            var expiry = $('#coupon_expiry').val();

            // Check if coupon contains only letters and numbers
            if (!/^[a-zA-Z0-9]+$/.test(coupon)) {
                $('#add_coupon_status').text('Coupon should contain only letters and numbers.').css('display', 'flex');
                return;
            }

            // Check if discount percent is between 1 and 100
            if (discountPercent < 1 || discountPercent > 100) {
                $('#add_coupon_status').text('Discount percent should be between 1 and 100.').css('display', 'flex');
                return;
            }

            $.ajax({
                url: 'coupons/add_coupon.php',
                type: 'POST',
                data: {
                    coupon: coupon,
                    discount_percent: discountPercent,
                    expiry: expiry
                },
                success: function(response) {
                    if (response == 'exists') {
                        $('#add_coupon_status').text('Coupon already exists.').css('display', 'flex');
                    } else if (response == 'inserted') {
                        $('#addCouponModal').hide();
                        showResponseModal('Coupon inserted successfully.', function() {
                            // Reload the coupons
                            $('#coupons-search-input').trigger('input');
                        });
                    } else {
                        alert('An error occurred.');
                    }
                }
            });
        });
    });

    // clear search
    $(document).ready(function() {
        $('#user-clear-search').click(function() {
            $('#coupons-search-input').val('');
            $('#coupons-search-input').trigger('input');
        });
    });

    // edit coupon
    $(document).ready(function() {
        $('.close').click(function() {
            $('#editCouponModal').hide();
        });

        $('#editCouponModal').click(function(event) {
            if (event.target == this) {
                $('#editCouponModal').hide();
            }
        });

        $(document).on('click', '.edit_coupon', function(e) {
            // Get the coupon data
            var coupon = $(this).data('coupon');

            // Fill the form with the coupon data
            $('#edit_coupon').text(coupon);
            $('#edit_discount_percent').val($(this).closest('.coupon_card').find('.discount_percent').text().replace('%', ''));
            $('#edit_coupon_expiry').val($(this).closest('.coupon_card').find('p').text().replace('Expiry: ', ''));

            // Show the modal
            $('#editCouponModal').show();
        });

        // update coupon
        $('#editCouponForm').on('submit', function(e) {
            e.preventDefault();

            // Get the form data
            var coupon = $('#edit_coupon').text();
            var discountPercent = $('#edit_discount_percent').val();
            var couponExpiry = $('#edit_coupon_expiry').val();

            // Check if coupon contains only letters and numbers
            if (!/^[a-zA-Z0-9]+$/.test(coupon)) {
                $('#edit_coupon_status').text('Coupon should contain only letters and numbers.').css('display', 'flex');
                return;
            }

            // Check if discount percent is between 1 and 100
            if (discountPercent < 1 || discountPercent > 100) {
                $('#edit_coupon_status').text('Discount percent should be between 1 and 100.').css('display', 'flex');
                return;
            }

            // Send the data to the PHP script
            $.ajax({
                url: 'coupons/update_coupon.php',
                type: 'POST',
                data: {
                    coupon: coupon,
                    discount_percent: discountPercent,
                    coupon_expiry: couponExpiry
                },
                success: function(response) {
                    $('#editCouponModal').hide();
                    showResponseModal(response, function() {
                        // Reload the coupons
                        $('#coupons-search-input').trigger('input');
                    });
                }
            });
        });
    });

    // delete coupon
    $(document).ready(function() {
        $(document).on('click', '.delete_coupon', function(e) {
            e.preventDefault();

            // Get the coupon code
            var coupon = $(this).data('coupon');

            var callback = function() {
                // Send an AJAX request to close the ticket
                $.ajax({
                    url: 'coupons/delete_coupon.php',
                    type: 'POST',
                    data: {
                        coupon: coupon
                    },
                    success: function(response) {
                        // Handle the response from the PHP script
                        showResponseModal(response, function() {
                            // Reload the coupons
                            $('#coupons-search-input').trigger('input');
                        });
                    }
                });
            };

            // Show the confirmation modal
            showConfirmationModal('Are you sure you want to delete this coupon?', callback);
        });
    });
</script>

<!-- $(document).on('click', '.close-ticket', function() {
var ticketId = $(this).data('ticket-id');

// Define the callback function
var callback = function() {
// Send an AJAX request to close the ticket
$.ajax({
url: 'dashboard/live_chat/close_ticket.php',
method: 'POST',
data: {
ticket_id: ticketId
},
success: function(data) {
showResponseModal('Ticket closed Successfully');
}
});
};

// Show the confirmation modal
showConfirmationModal('Are you sure you want to close this ticket?', callback);
}); -->