// Function to set up autocomplete functionality
function setupAutocomplete(inputFieldId, autocompleteUrl, autocompleteType) {
    // Event listener for input field changes
    $(inputFieldId).on('input', function () {
        console.log('Setting up autocomplete for ' + inputFieldId);
        // Check if the input field exists to avoid unnecessary function calls 
        if ($(inputFieldId).length > 0) {
            var query = $(this).val();

            if (query.length > 0) {
                // Make an AJAX request to retrieve autocomplete results
                $.ajax({
                    url: autocompleteUrl,
                    method: 'GET',
                    data: {
                        query: query,
                        type: autocompleteType
                    },
                    success: function (data) {
                        var results = JSON.parse(data);
                        $(inputFieldId + '_autocomplete').empty();

                        if (results.length > 0) {
                            console.log('AJAX request successful, data: ', results);
                            console.log('Autocomplete container: ', $(inputFieldId + '_autocomplete'));
                            // Iterate over the results and create a div for each
                            results.forEach(function (result) {
                                if (autocompleteType == 'product-barcode') {
                                    var resultDiv = $('<div>' + result.barcode + ' - ' + result.product_name + '</div>');
                                } else if (autocompleteType == 'product-name') {
                                    var resultDiv = $('<div>' + result.product_name + '</div>');
                                } else if (autocompleteType == 'product-manuf') {
                                    var resultDiv = $('<div>' + result.manufacturer + '</div>');
                                }
                                console.log('resultdiv: ', resultDiv);

                                // Event listener for when a result is clicked
                                resultDiv.on('click', function () {
                                    // Set the input field value to the clicked result
                                    if (autocompleteType == 'product-barcode') {
                                        $(inputFieldId).val(result.barcode);
                                    } else if (autocompleteType == 'product-name') {
                                        $(inputFieldId).val(result.product_name);
                                        // Set the barcode field value to the clicked result's barcode
                                        $('#product_barcode_input').val(result.barcode);
                                    } else if (autocompleteType == 'product-manuf') {
                                        $(inputFieldId).val(result.manufacturer);
                                    }
                                    $(inputFieldId + '_autocomplete').empty().hide();
                                });

                                // Append the result div to the autocomplete container
                                $(inputFieldId + '_autocomplete').append(resultDiv);
                            });

                            // Show the autocomplete container
                            $(inputFieldId + '_autocomplete').show();
                        } else {
                            // Hide the autocomplete container if there are no results
                            $(inputFieldId + '_autocomplete').hide();
                        }
                    }
                });
            } else {
                // Clear and hide the autocomplete container if the input field is empty
                $(inputFieldId + '_autocomplete').empty().hide();
            }
        }
    });
}

// Usage

// Set up autocomplete for the specified input field
setupAutocomplete('#product_barcode_input', '../PHP/autocomplete.php', 'product-barcode');
setupAutocomplete('#product_name_input', '../PHP/autocomplete.php', 'product-name');
setupAutocomplete('#product_manufacturer_input', '../PHP/autocomplete.php', 'product-manuf');