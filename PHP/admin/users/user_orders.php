<?php
$user_id = $_GET['user_id'];
?>
<header>
    <a id="back_arrow" href="#" onclick="event.preventDefault(); goBack();"><span class="material-symbols-outlined">arrow_back</span></a>
    <script>
        function goBack() {
            $('#users').click();
        }
    </script>
    <div id="order-search-bar">
        <!-- <select id="users-search-type">
        <option value="email">Email</option>
        <option value="account_status">Account Status</option>
        <option value="order_nb">Order#</option>
    </select> -->
        <input type="text" id="order-search-input" placeholder="Search...">
        <button id="order-clear-search"><span class='material-symbols-outlined'>close</span></button>
    </div>
    <span id="filters"><span class="material-symbols-outlined">filter_list</span><select id="sortOrder">
            <option value="DESC">Newest to Oldest</option><!-- default filter-->
            <option value="ASC">Oldest to Newest</option>
        </select></span>
</header>

<div class="orders">
    <!-- cards after retrieval and filtering dynamically loaded here -->
</div>
<script>
    // $('sortOrder').trigger('change'); // default filter

    $('#order-clear-search').click(function() {
        $('#order-search-input').val('');
        $('#order-search-input').trigger('input');
    });
    $('#sortOrder').change(function() {
        var sortOrder = $(this).val();

        $.ajax({
            url: 'users/fetch_orders.php',
            type: 'GET',
            data: {
                user_id: <?php echo $user_id; ?>,
                sort_order: sortOrder
            },
            success: function(data) {
                // Update the page with the fetched data
                $('.orders').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });
    }).trigger('change');

    $('#order-search-input').on('input', function() {
        var searchQuery = $(this).val();
        var sortOrder = $('#sortOrder').val();

        $.ajax({
            url: 'users/fetch_orders.php',
            type: 'GET',
            data: {
                user_id: <?php echo $user_id; ?>,
                sort_order: sortOrder,
                search_query: searchQuery
            },
            success: function(data) {
                // Update the page with the fetched data
                $('.orders').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });
    });
</script>