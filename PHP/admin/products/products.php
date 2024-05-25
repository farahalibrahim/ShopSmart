redirected products
<?php
include_once '../../accountFreezeModal.inc.php'; ?>
<section class="products_section">
    <h2 id="product_section_header">Manage Products</h2>
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
                    <input type="text" id="product_search_input" placeholder="Search...">
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
        </div>
    </div>
    <div id="search_results"></div>
</section>
<script>
    // if searching by category or tag, hide the search field and show the select
    document.getElementById('product_search_type').addEventListener('change', function() {
        var searchField = document.querySelector('.search_field');
        var searchInput = document.querySelector('#product_search_input');
        var searchSelectDiv = document.querySelector('.search_select');
        var searchSelect = document.querySelector('#product_search_select');

        if (this.value === 'category') {
            searchField.style.display = 'none';
            searchSelectDiv.style.display = 'block';

            // Clear the select options
            searchSelect.innerHTML = '';

            var categories = ['Bakery', 'Beverages', 'Cleaning Supplies', 'Dairy', 'Fruits & Vegetables', 'Meat & Chicken', 'Personal Care', 'Snacks'];

            // Populate the select options with categories
            categories.forEach(function(category) {
                var option = document.createElement('option');
                option.value = category;
                option.text = category;
                searchSelect.appendChild(option);
            });
        } else if (this.value === 'tag') {
            searchField.style.display = 'none';
            searchSelectDiv.style.display = 'block';

            // Clear the select options
            searchSelect.innerHTML = '';

            $.ajax({
                url: 'products/get_tags.php',
                method: 'GET',
                success: function(data) {
                    var tags = JSON.parse(data);
                    tags.forEach(function(tag) {
                        var option = document.createElement('option');
                        option.value = tag.tag;
                        option.text = tag.tag + " - " + tag.tag_title;
                        searchSelect.appendChild(option);
                    });
                }
            });
        } else if (this.value === 'barcode') {
            searchField.style.display = 'block';
            searchSelectDiv.style.display = 'none';

            // apply a pattern that only allows 16 digits
            // searchField.setAttribute('type', 'text');
            searchInput.setAttribute('pattern', '\\d{16}');
            searchInput.setAttribute('title', 'Please enter exactly 16 digits');
        } else if (this.value === 'product_name') {
            searchField.style.display = 'block';
            searchSelectDiv.style.display = 'none';

            // pattern that allows letters, digits, parentheses, +, ., x only
            // searchInput.setAttribute('type', 'text');
            searchInput.setAttribute('pattern', '[A-Za-z0-9()+.x ]{1,60}');
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

    // prevent entering character that doesn't match pattern
    document.getElementById('product_search_input').addEventListener('keypress', function(e) {
        var pattern = this.getAttribute('pattern');
        if (pattern) {
            var regex = new RegExp(pattern);
            if (!regex.test(e.key)) {
                e.preventDefault();
            }
        }
    });

    $(document).ready(function() {
        $('#product_search_type').trigger('change');
    });

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
</script>