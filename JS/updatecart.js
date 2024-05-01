$(document).ready(function () {
    $('.quantity-input').on('input', function (event) {
        event.stopImmediatePropagation();

        console.log('Input value changed');

        var barcode = $(this).data('barcode');
        var supermarketId = $(this).data('supermarketId');
        var quantity = $(this).val();

        $.ajax({
            url: 'updatecart.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                barcode: barcode,
                supermarket_id: supermarketId,
                quantity: quantity,
            }),
            success: function (data) {
                console.log(data);
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    });
});