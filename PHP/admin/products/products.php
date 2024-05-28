<!-- redirected products -->
<?php
include_once '../../accountFreezeModal.inc.php'; ?>

<div id="addModal" style="display: none;">
    <div class="modal-content">
        <form id="product_form" enctype="multipart/form-data" style="display: none;">
            <h3 class="form_header">Add New Product</h3>
            <div id="add_status"></div>
            <table>
                <tr>
                    <td>
                        <h5>Barcode</h5>
                    </td>
                    <td><input type="text" name="barcode" id="barcode" class="form-input" placeholder="xxxx xxxx xxxx xxxx" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Supermarket</h5>
                    </td>
                    <td>
                        <select name="supermarket" id="supermarket" class="form-input" required>
                            <?php include_once 'get_supermarkets.php'; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5>Product Name</h5>
                    </td>
                    <td><input type="text" name="product_name" id="product_name" class="form-input" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Manufacturer</h5>
                    </td>
                    <td><input type="text" name="manufacturer" id="manufacturer" class="form-input" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Product Image</h5>
                    </td>
                    <td><input type="file" name="product_image" id="product_image" class="form-input" accept="image/*" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Nutritional Facts</h5>
                    </td>
                    <td><input type="file" name="nutritional_facts" id="nutritional_facts" class="form-input" accept="image/*" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Quantity Type</h5>
                    </td>
                    <td>
                        <select name="quantity_type" id="quantity_type" class="form-input" required>
                            <option value="weight">Weight</option>
                            <option value="piece">Piece</option>
                            <option value="liquid">Liquid</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5>Quantity</h5>
                    </td>
                    <td><input type="number" name="quantity" id="quantity" class="form-input" placeholder="XXXX" required></td><span id="unit"></span>
                </tr>

                <tr>
                    <td>
                        <h5>Price</h5>
                    </td>
                    <td><input type="number" name="price" id="price" class="form-input" placeholder="$X.XX" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Expiry Date</h5>
                    </td>
                    <td><input type="date" name="expiry_date" id="expiry_date" class="form-input" min="<?php echo date('Y-m-d'); ?>" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Category</h5>
                    </td>
                    <td>
                        <select name="category" id="category" class="form-input" required>
                            <?php include 'get_category_options.php'; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5>Tag</h5>
                    </td>
                    <td>
                        <select name="tag" id="tag" class="form-input" required>
                            <?php include 'get_tags.php'; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <button type="submit" id="save_product"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
        <!-- <form id="offer_form" style="display: none;">
            <h3 class="form_header">Add Offer to Existing Product</h3>
        </form> -->
        <form id="tags_form" style="display: none;">
            <h3 class="form_header">Add New Product Tag</h3>
            <span><span>Current Tags:</span><select name="category" id="current_tags_select" class="form-input">
                    <?php include 'get_tags.php'; ?>
                </select></span><br>

            <span><span>New Tag</span><input type="text" name="new_category" id="new_tag" title="Only digits" placeholder="..." required class="form-input"></span><br>
            <span><span>Tag title</span><input type="text" name="new_category" id="new_tag_title" title="Only letters, spaces and & are allowed" placeholder="..." required class="form-input"></span><br>
            <button type="submit" id="add_tag_button"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
        <form id="category_form" style="display: none;">
            <h3 class="form_header">Add New Category</h3>
            <span><span>Current Categories</span><select name="category" id="current_categories_select" class="form-input">
                    <?php include 'get_category_options.php'; ?>
                </select></span><br>

            <span><span>New Category</span><input type="text" name="new_category" id="new_category" title="Only letters, spaces and & are allowed" placeholder="..." required class="form-input"></span><br>
            <button type="submit" id="add_category_button"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
    </div>
</div>
<script>
    $('#save_product').click(function(e) {
        e.preventDefault();
        var form = $('#product_form')[0];

        // Check form validity
        if (!form.checkValidity()) {
            return;
        }

        // Regular expressions
        var barcodeRegex = /^\d{16}$/;
        var productNameRegex = /^[a-zA-Z0-9()+\-x& ]+$/;
        var manufacturerRegex = /^[a-zA-Z ]+$/;

        // Get input values
        var barcode = $('#barcode').val();
        var productName = $('#product_name').val();
        var manufacturer = $('#manufacturer').val();

        // Check if input matches regular expressions
        if (!barcodeRegex.test(barcode)) {
            $('#add_status').text('Barcode must be exactly 16 digits.');
            return;
        }
        if (!productNameRegex.test(productName)) {
            $('#add_status').text('Product name can contain letters, numbers, parentheses, + - x &, and spaces only.');
            return;
        }
        if (!manufacturerRegex.test(manufacturer)) {
            $('#add_status').text('Manufacturer must contain letters only.');
            return;
        }

        var formData = new FormData(form);


        $.ajax({
            url: 'products/add_product.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addModal').hide();
                if (response === "Product added successfully") {
                    showResponseModal("Product added successfully", function() {
                        $('#addModal').show();
                        $('#product_form').find('input, select').val(''); //reset form
                    });
                } else if (response === "Product already exists at selected supermarket") {
                    showResponseModal("Product already exists at selected supermarket", function() {
                        $('#products').click(); // Refresh the page
                    });
                } else if (response === "Product wasn't added") {
                    showResponseModal("Product wasn't added", function() {
                        $('#addModal').show();
                    });
                } else if (response === "Only images are allowed") {
                    $('#add_status').text(response);
                } else {
                    console.log(response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });
</script>
<section class="products_section">
    <h2 id="product_section_header"><span>Manage</span> Products</h2>
    <div class="product_section_top">
        <div id="product_search_bar">
            <select id="product_search_type">
                <option value="barcode">Product Barcode</option>
                <option value="product_name">Product Name</option>
                <option value="supermarket_name">Supermarket</option>
                <option value="category">Category</option>
                <option value="tag">Tag</option>
            </select>
            <div class="search_method">
                <div class="search_field">
                    <input type="text" id="product_search_input" placeholder="Search..." pattern="\d{1,16}"> <!-- default for barcode search -->
                    <button id="product_clear_search"><span class='material-symbols-outlined'>close</span></button>
                </div>
                <div class="search_select" style="display: none;">
                    <!-- for tag and category search  -->
                    <!-- to be populated dynamically -->
                    <select id="product_search_select"></select>
                </div>
            </div>
        </div>
        <div id="product_add_button">
            <button id="add_product_button"><span class="material-symbols-outlined">add</span><span>Add</span></button>
            <div id="add_product_popup" style="display: none;">
                <div class="popup-option" id="product_option">Product</div>
                <!-- <div class="popup-option" id="offer_option">Offer</div> -->
                <div class="popup-option" id="tags_option">Tags</div>
                <div class="popup-option" id="category_option">Category</div>
            </div>
        </div>
    </div>
    <div id="search_results"></div>
</section>
<!-- add button -->
<script>
    // add product options popup
    document.getElementById('add_product_button').addEventListener('click', function() {
        var popup = document.getElementById('add_product_popup');
        if (popup.style.display === 'none') {
            popup.style.display = 'block';
        } else {
            popup.style.display = 'none';
        }
    });

    document.querySelectorAll('.popup-option').forEach(function(option) {
        option.addEventListener('click', function() {
            var modal = document.getElementById('addModal');
            var popup = document.getElementById('add_product_popup');

            // Hide all forms
            document.querySelectorAll('#modal-content form').forEach(function(form) {
                form.style.display = 'none';
            });

            // Show the appropriate form based on the clicked option
            switch (this.id) {
                case 'product_option':
                    document.getElementById('product_form').style.display = 'block';
                    // document.getElementById('offer_form').style.display = 'none';
                    document.getElementById('tags_form').style.display = 'none';
                    document.getElementById('category_form').style.display = 'none';
                    break;
                    // case 'offer_option':
                    //     document.getElementById('product_form').style.display = 'none';
                    //     document.getElementById('offer_form').style.display = 'block';
                    //     document.getElementById('tags_form').style.display = 'none';
                    //     document.getElementById('category_form').style.display = 'none';
                    //     break;
                case 'tags_option':
                    document.getElementById('product_form').style.display = 'none';
                    // document.getElementById('offer_form').style.display = 'none';
                    document.getElementById('tags_form').style.display = 'block';
                    document.getElementById('category_form').style.display = 'none';
                    break;
                case 'category_option':
                    document.getElementById('product_form').style.display = 'none';
                    // document.getElementById('offer_form').style.display = 'none';
                    document.getElementById('tags_form').style.display = 'none';
                    document.getElementById('category_form').style.display = 'block';
                    break;
            }

            // Show the modal
            modal.style.display = 'block';
            popup.style.display = 'none';
        });
    });

    // save new category
    $(document).ready(function() {
        // prevent entering character that doesn't match pattern
        $('#new_category').on('keypress paste', function(event) {
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!/^[a-zA-Z\s&]+$/.test(key)) {
                event.preventDefault();
            }
        });
        $('#add_category_button').click(function(event) {
            event.preventDefault();

            var newCategory = $('#new_category').val();

            $.ajax({
                type: 'POST',
                url: 'products/add_category.php',
                data: {
                    new_category: newCategory
                },
                success: function(response) {
                    $('#addModal').hide()
                    showResponseModal('Category added successfully', function() {
                        $('#products').click(); // Refresh the page
                    });
                }
            });
        });
    });

    // save new tag
    $(document).ready(function() {
        $('#new_tag').on('keypress paste', function(event) {
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!/^\d+$/.test(key)) {
                event.preventDefault();
            }
        });

        $('#new_tag_title').on('keypress paste', function(event) {
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!/^[a-zA-Z\s&]+$/.test(key)) {
                event.preventDefault();
            }
        });

        $('#add_tag_button').click(function(event) {
            event.preventDefault();

            var newTag = $('#new_tag').val();
            var newTagTitle = $('#new_tag_title').val();

            $.ajax({
                type: 'POST',
                url: 'products/check_and_add_tag.php',
                data: {
                    new_tag: newTag,
                    new_tag_title: newTagTitle
                },
                success: function(response) {
                    if (response === 'Tag added successfully') {
                        $('#addModal').hide()
                        showResponseModal('Tag added successfully', function() {
                            $('#products').click(); // Refresh the page
                        });
                    } else if (response === 'Tag already exists') {
                        $('#addModal').hide()
                        showResponseModal('Tag already exists', function() {
                            $('#addModal').show()
                        });
                    }
                }
            });
        });
    });

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        var modal = document.getElementById('addModal');
        var modal2 = document.getElementById('productActionsModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        } else if (event.target == modal2) {
            modal2.style.display = 'none';
        }
    };
</script>
<!-- search and product display -->
<script>
    // Clear the search input when the clear button is clicked
    document.getElementById('product_clear_search').addEventListener('click', function() {
        document.getElementById('product_search_input').value = '';
    });
    // if searching by category or tag, hide the search field and show the select
    document.getElementById('product_search_type').addEventListener('change', function() {
        var searchField = document.querySelector('.search_field');
        var searchInput = document.querySelector('#product_search_input');
        var searchSelectDiv = document.querySelector('.search_select');
        var searchSelect = document.querySelector('#product_search_select');

        $('#product_search_input').trigger('input');
        $('#product_search_select').trigger('change');


        if (this.value === 'category') {
            searchField.style.display = 'none';
            searchSelectDiv.style.display = 'block';

            // Clear the select options
            searchSelect.innerHTML = '';

            $(document).ready(function() {
                $.ajax({
                    type: 'GET',
                    url: 'products/get_category_options.php',
                    success: function(response) {
                        // var searchSelect = document.getElementById('current_categories_select');

                        searchSelect.innerHTML = response;
                    }
                });
            });
        } else if (this.value === 'tag') {
            searchField.style.display = 'none';
            searchSelectDiv.style.display = 'block';

            // Clear the select options
            searchSelect.innerHTML = '';

            $.ajax({
                url: 'products/get_tags.php',
                method: 'GET',
                success: function(response) {
                    searchSelect.innerHTML = response;
                }
            });
        } else if (this.value === 'barcode') {
            searchField.style.display = 'block';
            searchSelectDiv.style.display = 'none';

            console.log(this.value);

            // apply a pattern that only allows 16 digits
            // searchField.setAttribute('type', 'text');
            searchInput.setAttribute('pattern', '[0-9]{1,16}');
            searchInput.setAttribute('title', 'Please enter up to 16 digits');
        } else if (this.value === 'product_name') {
            searchField.style.display = 'block';
            searchSelectDiv.style.display = 'none';

            // pattern that allows letters, digits, parentheses, +, ., x only
            // searchInput.setAttribute('type', 'text');


            searchInput.setAttribute('pattern', '^[a-zA-Z0-9()+.x\\s]{1,60}$');
            searchInput.setAttribute('title', 'Please enter up to 60 characters. Allowed characters: letters, digits, parentheses, +, ., x');
        } else if (this.value === 'supermarket_name') {
            searchField.style.display = 'block';
            searchSelectDiv.style.display = 'none';

            // pattern that allows letters only
            // searchInput.setAttribute('type', 'text');
            searchInput.setAttribute('pattern', '[A-Za-z ]{1,60}');
            searchInput.setAttribute('title', 'Please enter up to 60 characters. Allowed characters: letters');
        } else {
            searchField.style.display = 'block';
            searchSelectDiv.style.display = 'none';

            // Reset the input type and pattern
            // searchInput.setAttribute('type', 'text');
            searchInput.removeAttribute('pattern');
            searchInput.removeAttribute('title');
        }
    });

    //trigger select to load initial content
    $(document).ready(function() {
        $('#product_search_type').trigger('change');
    });


    // prevent entering character that doesn't match pattern
    $('#product_search_input').on('keypress paste', function(event) {
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        var pattern = this.getAttribute('pattern');

        if (pattern) {
            var regex = new RegExp('^' + pattern + '$');
            if (!regex.test(key)) {
                event.preventDefault();
            }
        }

    });

    $(document).ready(function() {
        $('#product_search_type').trigger('change');
    });

    // search for products
    $(document).ready(function() {
        $('#product_search_select').change(function() {
            var searchType = $('#product_search_type').val();
            var searchSelectValue = $(this).val();

            $.ajax({
                url: 'products/search_products.php',
                type: 'POST',
                data: {
                    searchType: searchType,
                    searchSelectValue: searchSelectValue
                },
                success: function(response) {
                    $('#search_results').html(response);
                }
            });
        });

        $('#product_search_input').keyup(function() {
            var searchType = $('#product_search_type').val();
            var searchInputValue = $(this).val();

            $.ajax({
                url: 'products/search_products.php',
                type: 'POST',
                data: {
                    searchType: searchType,
                    searchSelectValue: searchInputValue
                },
                success: function(response) {
                    $('#search_results').html(response);
                }
            });
        });
    });

    $(document).on('click', '.delete-button', function() {
        var barcode = $(this).data('barcode');
        var supermarketId = $(this).data('supermarket-id');

        showConfirmationModal('Are you sure you want to delete this product?', function() {
            $.ajax({
                url: 'products/delete_product.php',
                type: 'POST',
                data: {
                    barcode: barcode,
                    supermarket_id: supermarketId
                },
                success: function(response) {
                    // console.log('Success:', response);
                    if (response == 'success') {
                        showResponseModal('Product deleted successfully', function() {
                            $('#product_search_input').trigger('keyup');
                        });
                    } else {
                        showResponseModal('Failed to delete product');
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    });

    $(document).on('click', '.edit-button', function() {
        var barcode = $(this).data('barcode');
        var supermarketId = $(this).data('supermarket-id');


        $('#edit_barcode').val(barcode);
        $('#edit_supermarket').val(supermarketId);

        $('#offerForm').hide();
        $('#editForm').show();

        $('#productActionsModal').show();

        // $.ajax({
        // url: 'products/get_product.php',
        // type: 'POST',
        // data: {
        // barcode: barcode,
        // supermarket_id: supermarketId
        // },
        // success: function(data) {
        // var product = JSON.parse(data);

        // if (product) {
        // // Fill the form fields with the product data
        // $('#edit_product_name').val(product.product_name);
        // $('#edit_manufacturer').val(product.manufacturer);
        // $('#edit_quantity_type').val(product.quantity_type);
        // $('#edit_quantity').val(product.quantity);
        // $('#edit_price').val(product.price);
        // $('#edit_expiry_date').val(product.expiry_date);
        // $('#edit_category').val(product.category);
        // $('#edit_tag').val(product.tag);

        // $('#editForm').show();
        // } else {
        // alert('No product found with the given barcode and supermarket ID.');
        // }
        // },
        // error: function(jqXHR, textStatus, errorThrown) {
        // console.error('Error:', textStatus, errorThrown);
        // }
        // });
    });
    $(document).on('click', '.offer-button', function() {

        var barcode = $(this).data('barcode');
        var supermarketId = $(this).data('supermarket-id');

        $.ajax({
            url: 'products/get_offer.php',
            type: 'POST',
            data: {
                barcode: barcode,
                supermarket_id: supermarketId
            },
            success: function(data) {
                if (data === "No offer") {

                    $('#offerForm').show();
                    $('#editForm').hide();


                    $('#offer_barcode').val(barcode);
                    $('#offer_supermarket').val(supermarketId);

                    $('#productActionsModal').show();

                } else {
                    var offer = JSON.parse(data);

                    // Populate the offer form with the offer data
                    $('#offer_percent').val(offer.offer_percent);
                    $('#offer_expiry').val(offer.offer_expiry);

                    $('#offer_barcode').val(barcode);
                    $('#offer_supermarket').val(supermarketId);

                    $('#offerForm').show();
                    $('#editForm').hide();

                    $('#productActionsModal').show();
                }
            }
        });
    });
</script>
<div id="productActionsModal" style="display: none;">
    <div class="modal-content">
        <form id="editForm" style="display: none;">
            <h3 class="form_header">Edit Product</h3>
            <!-- <div id="add_status"></div> -->
            <table>
                <input type="hidden" name="edit_barcode" id="edit_barcode">
                <input type="hidden" name="edit_supermarket" id="edit_supermarket">
                <tr>
                    <td>
                        <h5>Product Name</h5>
                    </td>
                    <td><input type="text" name="product_name" id="edit_product_name" class="form-input" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Manufacturer</h5>
                    </td>
                    <td><input type="text" name="manufacturer" id="edit_manufacturer" class="form-input" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Quantity Type</h5>
                    </td>
                    <td>
                        <select name="quantity_type" id="edit_quantity_type" class="form-input" required>
                            <option value="weight">Weight</option>
                            <option value="piece">Piece</option>
                            <option value="liquid">Liquid</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5>Quantity</h5>
                    </td>
                    <td><input type="number" name="quantity" id="edit_quantity" class="form-input" placeholder="XXXX" min="1" max="15000" required></td><span id="unit"></span>
                </tr>

                <tr>
                    <td>
                        <h5>Price</h5>
                    </td>
                    <td><input type="number" name="price" id="edit_price" class="form-input" placeholder="$X.XX" min="0" max="1000" step="0.1" required></td>

                </tr>
                <tr>
                    <td>
                        <h5>Expiry Date</h5>
                    </td>
                    <td><input type="date" name="expiry_date" id="edit_expiry_date" class="form-input" min="<?php echo date('Y-m-d'); ?>" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Category</h5>
                    </td>
                    <td>
                        <select name="category" id="edit_category" class="form-input" required>
                            <?php include 'get_category_options.php'; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5>Tag</h5>
                    </td>
                    <td>
                        <select name="tag" id="edit_tag" class="form-input" required>
                            <?php include 'get_tags.php'; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <button type="submit" id="edit_product"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
        <form id="offerForm" style="display: none;">
            <h3 class="form_header">Add Offer</h3>
            <div class="offer_status"></div>
            <table>
                <input type="hidden" name="offer_barcode" id="offer_barcode">
                <input type="hidden" name="offer_supermarket" id="offer_supermarket">
                <tr>
                    <td>
                        <h5>Offer Percent</h5>
                    </td>
                    <td><input type="number" name="offer_percent" id="offer_percent" class="form-input" min="1" max="100" step="1" required></td>
                </tr>
                <tr>
                    <td>
                        <h5>Offer End Date</h5>
                    </td>
                    <td><input type="date" name="offer_expiry" id="offer_expiry" class="form-input" min="<?php echo date('Y-m-d'); ?>" required></td> <!-- min current date-->
                </tr>
            </table>
            <button type="submit" id="add_offer"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
    </div>
</div>
<script>
    $(document).on('click', '#add_offer', function(e) {
        e.preventDefault();
        $('.offer_status').text('');

        var offerPercent = $('#offer_percent').val();
        var offerExpiry = $('#offer_expiry').val();
        var offerBarcode = $('#offer_barcode').val();
        var offerSupermarket = $('#offer_supermarket').val();

        if (!offerPercent || !offerExpiry) {
            $('.offer_status').text('Both fields must be filled');
            return;
        }

        // Validate the offer percent
        if (offerPercent < 1 || offerPercent > 100) {
            $('.offer_status').text('Offer percent must be between 1 and 100.');
            return;
        }

        $.ajax({
            url: 'products/update_offer.php',
            type: 'POST',
            data: {
                offer_percent: offerPercent,
                offer_expiry: offerExpiry,
                offer_barcode: offerBarcode,
                offer_supermarket: offerSupermarket
            },
            success: function(response) {
                $('#productActionsModal').hide();
                if (response === 'success') {
                    showResponseModal('Offer added successfully', function() {
                        $('#product_search_input').trigger('keyup');
                    });
                } else {
                    showResponseModal('Failed to add offer', function() {
                        $('#productActionsModal').show();
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    });
    $(document).ready(function() {
        $(document).on('click', '#edit_product', function(e) {
            e.preventDefault();

            var barcode = $('#edit_barcode').val();
            var supermarketId = $('#edit_supermarket').val();
            var productName = $('#edit_product_name').val();
            var manufacturer = $('#edit_manufacturer').val();
            var quantityType = $('#edit_quantity_type').val();
            var quantity = $('#edit_quantity').val();
            var price = $('#edit_price').val();
            var expiryDate = $('#edit_expiry_date').val();
            var category = $('#edit_category').val();
            var tag = $('#edit_tag').val();

            $.ajax({
                url: 'products/update_product.php',
                type: 'POST',
                data: {
                    edit_barcode: barcode,
                    edit_supermarket: supermarketId,
                    product_name: productName,
                    manufacturer: manufacturer,
                    quantity_type: quantityType,
                    quantity: quantity,
                    price: price,
                    expiry_date: expiryDate,
                    category: category,
                    tag: tag
                },
                success: function(response) {
                    $('#productActionsModal').hide();
                    if (response === 'success') {
                        showResponseModal('Offer added successfully', function() {
                            $('#product_search_input').trigger('keyup');
                        });
                    } else {
                        showResponseModal('Failed to add offer', function() {
                            $('#productActionsModal').show();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                }
            });
        });
    });
</script>