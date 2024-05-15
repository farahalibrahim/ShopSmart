$(document).ready(function () {
    // Loop through each element with the class 'order'
    $('.order').each(function () {
        var order = $(this); // Get the current order element
        var button = order.find('.pack-order'); // Find the button element within the order element

        // Add event listener to checkboxes within the order element
        order.find('input[type="checkbox"]').on('change', function () {
            // Check if all checkboxes are checked
            var allChecked = order.find('input[type="checkbox"]:not(:checked)').length === 0;
            button.prop('disabled', !allChecked); // Enable/disable the button based on checkbox status
        });

        // Add event listener to the button element
        button.on('click', function () {
            // Send a POST request to 'pack_order.php' with the order data
            $.post('../packing/pack_order.php', { order: order.data('order') }, function (response) {
                if (response.success) {
                    order.remove(); // Remove the order element if the response is successful
                } else {
                    alert('Failed to pack order.'); // Show an alert if the response is not successful
                }
            }, 'json');
        });
    });
});