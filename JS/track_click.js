$(document).ready(function () {
    $('.click_product').on('click', function () {
        var product = $(this).data('barcode');
        console.log("Product clicked ");

        $.ajax({
            url: '../PHP/track_click.php',
            type: 'POST',
            data: {
                barcode: product
            },
            success: function (data) {
                console.log("Product click tracked successfully");
            },
            error: function (error) {
                console.log("Error tracking product click: ", error);
            }
        });
    });
});