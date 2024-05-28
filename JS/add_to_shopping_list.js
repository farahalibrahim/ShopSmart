
var userId = getCookie('user_id');

$(document).on('click', '.add_to_list', function () {
    var barcode = $(this).data("barcode");
    var supermarket_id = $(this).data("supermarket-id");

    if (!userId) {
        showResponseModal('Please login to to track this product in shopping list');
        return;
    }


    // Send AJAX request to add_to_shopping_list.php
    $.ajax({
        url: 'http://localhost:3000/PHP/shopping_list/add_to_shopping_list.php',
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
function getCookie(name) {
    // cookies are stored as cookiename = cookievalue; ... so we need to split by ; and then find our name value pair
    // nameEQ is the name we are looking for, name of the cookie + '=' to match the structure cookies are stored in
    var nameEQ = name + "=";
    // due to the structure of cookies, we need to split by ; to find our name value pair
    var ca = document.cookie.split(';');
    // loop through all the cookies until we find a match for our cookie name
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        // remove leading whitespace, check if first char is space if yes form string after it
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        // if the cookie name is found return the value of the cookie
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}