<!-- redirected supermarkets -->

<style>
    .chart {
        width: 100%;
    }
</style>

<div class="supermarkets">
    <section class="supermarket_analytics">
        <h2 class="supermarket_analytics_header">Sales Analytics</h2>
        <div id="analytics_filters">
            <select id="sales_analytics">
                <option value="total_sales">Total Sales</option>
                <option value="top_selling_products">Top Selling Products</option>
                <option value="number_of_orders">Number of Orders</option>
                <option value="average_order_value">Average Order Value</option>
            </select>

            <select id="analysis_duration">
                <option value="current_month">Current Month</option>
                <option value="3_months">3 Months</option>
                <option value="6_months">6 Months</option>
                <option value="1_year">1 Year</option>
            </select>
        </div>
        <div class="chart"></div>
    </section>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {
            'packages': ['corechart']
        });

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the column chart, passes in the data and
        // draws it.
        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Supermarket');
            data.addColumn('number', 'Value');

            // Set chart options
            var options = {
                'title': 'Sales Analytics',
                'width': 500,
                'height': 300,
                'colors': ['green'],
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.querySelector('.chart'));

            // Listen for change events on the select elements
            $('#sales_analytics, #analysis_duration').change(function() {
                var salesAnalytics = $('#sales_analytics').val();
                var analysisDuration = $('#analysis_duration').val();

                options.title = $('#sales_analytics option:selected').text() + ' per Supermarket';
                options.legend = {
                    position: 'none'
                };

                // Send AJAX request to PHP script
                $.ajax({
                    type: 'POST',
                    url: 'supermarkets/analytics.php',
                    data: {
                        'sales_analytics': salesAnalytics,
                        'analysis_duration': analysisDuration
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response);

                        if (Array.isArray(response)) {
                            // Clear the data
                            data.removeRows(0, data.getNumberOfRows());

                            // Add a row to the data table for each supermarket
                            if (salesAnalytics === 'top_selling_products') {
                                // For top selling products, the response should be an array of objects
                                // where each object has a 'product_name', 'manufacturer', 'name' property and a 'quantity' property
                                var supermarkets = {};
                                response.forEach(function(product) {
                                    var quantity = parseFloat(product.quantity);
                                    if (!isNaN(quantity)) {
                                        // Check if this supermarket's product has already been added
                                        if (!supermarkets[product.name]) {
                                            // Concatenate product name, manufacturer, and supermarket for the x-axis label
                                            var label = product.name + ', ' + product.product_name + ', ' + product.manufacturer;
                                            data.addRow([label, quantity]);
                                            supermarkets[product.name] = true;
                                        }
                                    }
                                });
                            } else {
                                // For other analytics, the response should be an array of objects
                                // where each object has a 'name' property and a 'value' property
                                response.forEach(function(supermarket) {
                                    var value = parseFloat(supermarket[salesAnalytics]);
                                    if (!isNaN(value)) {
                                        data.addRow([supermarket.name, value]);
                                    }
                                });
                            }
                            // Format the tooltip based on the selected analytic
                            var formatter = new google.visualization.NumberFormat();
                            if (salesAnalytics === 'average_order_value') {
                                formatter = new google.visualization.NumberFormat({
                                    prefix: '$'
                                });
                            } else if (salesAnalytics === 'total_sales') {
                                formatter = new google.visualization.NumberFormat({
                                    prefix: 'Sales: $'
                                });
                            } else if (salesAnalytics === 'number_of_orders') {
                                formatter = new google.visualization.NumberFormat({
                                    prefix: 'Orders: '
                                });
                            } else if (salesAnalytics === 'top_selling_products') {
                                formatter = new google.visualization.NumberFormat({
                                    suffix: ' times'
                                });
                            }
                            formatter.format(data, 1); // Apply formatter to second column of data

                            // Draw the chart with the new data
                            chart.draw(data, options);
                        } else {
                            console.error('Unexpected response type:', typeof response);
                        }
                    }
                });
            });

            // Trigger the change event to draw the chart initially
            $('#sales_analytics').change();
        }
    </script>


    <section class="supermarket_managment">
        <h2 class="supermarket_managment_header">Manage Supermarkets</h2>
        <table class="supermarket_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Phone</th>
                    <!-- <th>Rating</th>
                    <th>Number of Ratings</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                include_once '../../connection.inc.php';
                include_once '../../dbh.class.inc.php';

                try {
                    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

                    $stmt = DatabaseHelper::runQuery($conn, "SELECT * FROM supermarket");
                    $supermarkets = $stmt->fetchAll();

                    foreach ($supermarkets as $supermarket) {
                        echo '<tr>';
                        echo '<td>' . $supermarket['id'] . '</td>';
                        echo '<td>' . $supermarket['name'] . '</td>';
                        echo '<td><span>' . $supermarket['email'] . '</span><a href="https://mail.google.com/mail/?view=cm&fs=1&to=' . $supermarket['email'] . '" target="_blank"><span class="material-symbols-outlined">alternate_email</span></a></td>';
                        echo '<td><span>' . $supermarket['website'] . '</span><a href="https://' . $supermarket['website'] . '" target="_blank"><span class="material-symbols-outlined">globe</span></a></td>';
                        echo '<td data-phone="' . $supermarket['phone'] . '">' . $supermarket['phone'] . '</td>';
                        echo '<td><button class="edit-button" type="button" data-id="' . $supermarket['id'] . '" data-name="' . $supermarket['name'] . '" data-website="' . $supermarket['website'] . '" data-email="' . $supermarket['email'] . '" data-phone="' . $supermarket['phone'] . '"><span class="material-symbols-outlined">edit</span></button></td>';
                        // echo '<td>' . $supermarket['rating'] . '</td>';
                        // echo '<td>' . $supermarket['nb_of_ratings'] . '</td>';
                        echo '</tr>';
                    }
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                ?>
            </tbody>
    </section>
</div>
<div id="supermarket_modal" class="modal">
    <div class="modal-content">
        <span id="close" class="close-button">&times;</span>
        <form class="supermarket_form" action="#">
            <input type="hidden" id="id" name="id" class="form-input">
            <div class="form-field">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-input">
            </div>
            <div class="form-field">
                <label for="website" class="form-label">Website:</label>
                <input type="text" id="website" name="website" class="form-input">
            </div>
            <div class="form-field">
                <label for="email" class="form-label">Email:</label>
                <input type="text" id="email" name="email" class="form-input">
            </div>
            <div class="form-field">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-input">
            </div>
            <input type="submit" value="submit" id="form-submit-button">
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#supermarket_modal .close').click(function() {
            $('#supermarket_modal').hide();
        });
    });
</script>

<script>
    // auto-format phone input contents based on number format for db contents for phone
    $(document).ready(function() {
        $('#phone').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, '');
            if (number.length > 11) {
                number = number.slice(0, 11);
            }
            if (number.length == 7) {
                number = number.replace(/(\d{3})(\d{4})/, "$1-$2"); // 123-4567 format
            } else if (number.length == 10) {
                number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3"); // (123) 456-7890 format
            } else if (number.length == 11) {
                number = number.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, "$1-$2-$3-$4"); // 1-123-456-7890 format
            }
            $(this).val(number);
        });

        $('#phone').on('keypress', function(e) {
            if (e.which < 48 || e.which > 57) { //prevent other than number 0-9
                e.preventDefault();
            }
        });
    });
    $(document).ready(function() {
        var $modal = $("#supermarket_modal");
        var $form = $(".supermarket_form");

        $("#close").click(function() {
            $modal.hide();
        });

        $(window).click(function(event) {
            if (event.target == $modal[0]) {
                $modal.hide();
            }
        });

        $(document).on('click', '.edit-button', function() {
            $("#id").val($(this).data("id"));
            $("#name").val($(this).data("name"));
            $("#website").val($(this).data("website"));
            $("#email").val($(this).data("email"));
            $("#phone").val($(this).data("phone"));
            $modal.show();
        });
    });
    $('#form-submit-button').click(function(e) {
        e.preventDefault();

        console.log('button triggered');
        var id = $("#id").val();
        var name = $("#name").val();
        var website = $("#website").val();
        var email = $("#email").val();
        var phone = $("#phone").val();

        var nameRegex = /^[a-zA-Z0-9]+$/;
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        var phoneRegex = /^1?[-.\s]?\(?(\d{3})\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;

        if (nameRegex.test(name) && emailRegex.test(email) && phoneRegex.test(phone)) {
            $.ajax({
                type: "POST",
                url: "supermarkets/update_supermarket.php",
                data: {
                    id: id,
                    name: name,
                    website: website,
                    email: email,
                    phone: phone
                },
                success: function(response) {
                    if (response == 'success') {
                        $("#supermarket_modal").hide();
                        showResponseModal("Information for " + name + " has been updated successfully", function() {
                            $("#supermarkets").click(); // reload the page to show the updated data
                        });
                    } else {
                        $("#supermarket_modal").hide();
                        showResponseModal("Information for " + name + " Haven't been updated", function() {
                            $("#supermarkets").click(); // reload the page to show the updated data
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('An error occurred: ' + textStatus);
                }
            });
        } else {
            if (!nameRegex.test(name)) {
                $("#supermarket_modal").hide();
                showResponseModal("No special characters allowed", function() {
                    $("#supermarket_modal").show();
                });
            }
            if (!emailRegex.test(email)) {
                $("#supermarket_modal").hide();
                showResponseModal("Email must  match example@example.com", function() {
                    $("#supermarket_modal").show();
                });
            }
            if (!phoneRegex.test(phone)) {
                $("#supermarket_modal").hide();
                showResponseModal("Phone must match either (XXX) XXX-XXXX or 1-XXX-XXX-XXXX", function() {
                    $("#supermarket_modal").show();
                });
            }
        }
    });
</script>


<!-- id	
name	
email	
website	
phone	
rating	
nb_of_ratings	 -->


<!-- order_nb
user_id
order_date
status
order_total
payment_method
delivery_date -->

<!-- order_nb
product_barcode
supermarket_id
quantity
price -->


<!-- <div id="total_sales_chart" class="chart"></div>
        <div id="number_of_orders_chart" class="chart"></div>
        <div id="average_order_value_chart" class="chart"></div>
        <div id="top_selling_products_chart" class="chart"></div> -->

<script>
    // function drawCharts() {
    // // Fetch the data from the server
    // $.ajax({
    // url: 'supermarkets/fetch_sales_data.php',
    // type: 'GET',
    // success: function(response) {
    // console.log('Server response:', response);
    // var data;
    // try {
    // data = JSON.parse(response);
    // } catch (error) {
    // console.error('Failed to parse server response:', error);
    // return;
    // }
    // console.log('Parsed data:', data);

    // // Convert the data to the correct format
    // var totalSales = [
    // ['Supermarket', 'Sales']
    // ];
    // data.totalSales.forEach(function(row) {
    // totalSales.push([row.supermarket_name, {
    // v: parseFloat(row.total_sales),
    // f: '$' + parseFloat(row.total_sales).toFixed(2)
    // }]);
    // });

    // var numberOfOrders = [
    // ['Supermarket', 'Orders']
    // ];
    // data.numberOfOrders.forEach(function(row) {
    // numberOfOrders.push([row.supermarket_name, parseInt(row.number_of_orders)]);
    // });

    // var averageOrderValue = [
    // ['Supermarket', 'Average Order Value']
    // ];
    // data.averageOrderValue.forEach(function(row) {
    // averageOrderValue.push([row.supermarket_name, {
    // v: parseFloat(row.average_order_value),
    // f: '$' + parseFloat(row.average_order_value).toFixed(2)
    // }]);
    // });

    // var topSellingproduct = [
    // ['Product Name', 'Total Quantity']
    // ];
    // data.topSellingproduct.forEach(function(row) {
    // var productName = row.product_name.split(' ').slice(0, 2).join(' ') + ' (' + row.supermarket_name + ')';
    // topSellingproduct.push([productName, parseInt(row.total_quantity)]);
    // });

    // // Group the products by supermarket
    // var productsBySupermarket = {};
    // data.topSellingproduct.forEach(function(row) {
    // if (!productsBySupermarket[row.supermarket_name]) {
    // productsBySupermarket[row.supermarket_name] = [];
    // }
    // productsBySupermarket[row.supermarket_name].push(row);
    // });

    // // Sort the products by quantity and take the top 3 for each supermarket
    // for (var supermarket in productsBySupermarket) {
    // if (productsBySupermarket[supermarket]) {
    // productsBySupermarket[supermarket].sort(function(a, b) {
    // return parseInt(b.total_quantity) - parseInt(a.total_quantity);
    // });
    // productsBySupermarket[supermarket] = productsBySupermarket[supermarket].slice(0, 3);
    // }
    // }

    // // Flatten the products into a single array
    // var topSellingproduct = [
    // ['Product Name', 'Total Quantity']
    // ];
    // for (var supermarket in productsBySupermarket) {
    // productsBySupermarket[supermarket].forEach(function(row) {
    // var productName = row.product_name.split(' ').slice(0, 2).join(' ') + ' (' + supermarket + ')';
    // topSellingproduct.push([productName, parseInt(row.total_quantity)]);
    // });
    // }

    // // Sort the products by supermarket name
    // topSellingproduct.sort(function(a, b) {
    // var supermarketA = a[0].split(' (')[1].slice(0, -1);
    // var supermarketB = b[0].split(' (')[1].slice(0, -1);
    // return supermarketA.localeCompare(supermarketB);
    // });


    // // Draw the charts
    // var totalSalesData = google.visualization.arrayToDataTable(totalSales);
    // var totalSalesOptions = {
    // title: 'Total Sales per Supermarket',
    // legend: {
    // position: 'top',
    // alignment: 'start'
    // }
    // };
    // var totalSalesChart = new google.visualization.ColumnChart(document.getElementById('total_sales_chart'));
    // totalSalesChart.draw(totalSalesData, totalSalesOptions);

    // var numberOfOrdersData = google.visualization.arrayToDataTable(numberOfOrders);
    // var numberOfOrdersOptions = {
    // title: 'Number of Orders per Supermarket',
    // legend: {
    // position: 'top',
    // alignment: 'start'
    // }
    // };
    // var numberOfOrdersChart = new google.visualization.ColumnChart(document.getElementById('number_of_orders_chart'));
    // numberOfOrdersChart.draw(numberOfOrdersData, numberOfOrdersOptions);

    // var averageOrderValueData = google.visualization.arrayToDataTable(averageOrderValue);
    // var averageOrderValueOptions = {
    // title: 'Average Order Value per Supermarket',
    // legend: {
    // position: 'top',
    // alignment: 'start'
    // }
    // };
    // var averageOrderValueChart = new google.visualization.ColumnChart(document.getElementById('average_order_value_chart'));
    // averageOrderValueChart.draw(averageOrderValueData, averageOrderValueOptions);

    // var topSellingproductData = google.visualization.arrayToDataTable(topSellingproduct);
    // var topSellingproductOptions = {
    // title: 'Top Selling Products per Supermarket',
    // legend: {
    // position: 'top',
    // alignment: 'start'
    // }
    // };
    // var topSellingproductChart = new google.visualization.ColumnChart(document.getElementById('top_selling_products_chart'));
    // topSellingproductChart.draw(topSellingproductData, topSellingproductOptions);
    // }
    // });
    // }
</script>