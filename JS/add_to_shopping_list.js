$(document).on('click', '.add_to_list', function () {
    var barcode = $(this).data("barcode");
    var supermarket_id = $(this).data("supermarket-id");

    // Send AJAX request to add_to_shopping_list.php
    $.ajax({
        url: 'add_to_shopping_list.php',
        type: 'post',
        data: {
            barcode: barcode,
            supermarket_id: supermarket_id
        },
        success: function (result) {
            // Handle success here, e.g. show a success message

            $('#ShoppingListModal').hide();
            showResponseModal("Product added to shopping list", function () {
                location.reload();
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle error here, e.g. show an error message
            showResponseModal("Failed to add product to shopping list: " + errorThrown);
        }
    });
});