// check if user logged in
var userId = getCookie('user_id');
var buttons = $('.add_to_cart_btn');

buttons.each(function () {
    $(this).on('click', function () {
        if (!userId) {
            alert('Please login to add items to cart.');
            return;
        }

        var barcode = $(this).data('barcode');
        var supermarketId = $(this).data('supermarket-id');

        $.ajax({
            url: '../PHP/addtocart.php',
            type: 'POST',
            data: {
                barcode: barcode,
                supermarket_id: supermarketId
            },
            success: function () {
                alert('Product added to cart');
            }
        });
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