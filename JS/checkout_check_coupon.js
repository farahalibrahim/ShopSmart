$(document).ready(function () {
    // $('#apply_coupon').click(function () {
    $('#coupon_input').on('change click', function () {
        var couponCode = $('#coupon_input').val();
        $.ajax({
            type: 'POST',
            url: '../cart_checkout/check_coupon.php',
            data: { coupon: couponCode },
            async: false, // Make the AJAX request synchronous
            success: function (response) {
                if (response === '0') {
                    $('#coupon_status').show().text('Coupon invalid!');
                } else {
                    $('#coupon_status').show().text('Hooray! You got ' + response + '% discount');

                    // Send the discount to update_cart_summary.php
                    $.ajax({
                        type: 'POST',
                        url: '../cart_checkout/update_cart_summary.php', // Replace with the path to your PHP script
                        data: { discount: response },
                        success: function (cartSummary) {
                            // Update the cart summary with the new discount value
                            $('.total').html(cartSummary);
                            // Update the 'total' element with the value of the 'placeorder_total' element
                            $('#total').val($('#placeorder_total').val());
                        }
                    });
                }
            }
        });
    });
});