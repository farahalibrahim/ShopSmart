$(document).ready(function () {
    $('.delete-button').click(function (event) {
        event.stopImmediatePropagation();

        var barcode = $(this).data('barcode');
        var supermarketId = $(this).data('supermarketId');

        $.ajax({
            url: '../cart_checkout/delete_from_cart.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                barcode: barcode,
                supermarket_id: supermarketId,
            }),
            success: function (response) {
                console.log(response);
                // Reload the page
                location.reload();
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    });
});