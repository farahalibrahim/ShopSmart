<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$sortOrder = $_GET['sort_order'] ?? 'DESC'; // default sort order
$user_id = $_GET['user_id'];
$searchQuery = $_GET['search_query'] ?? ''; // default search query

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);


    $sql = "SELECT `order`.order_nb,`order`.`status`, COUNT(order_details.order_nb) as nb_products, `order`.order_total, `order`.order_date
            FROM `order`
            LEFT JOIN order_details ON `order`.order_nb = order_details.order_nb
            WHERE `order`.user_id = :user_id
            AND (`order`.order_nb LIKE :search_query OR `order`.`status` LIKE :search_query)
            GROUP BY `order`.order_nb
            ORDER BY `order`.order_date $sortOrder";

    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id, 'search_query' => "$searchQuery%"]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<h3 class="card-title">Order# ' . $row['order_nb'] . '</h3>';
        // echo '<p class="card-text">Number of Products: ' . $row['nb_products'] . '</p>';
        echo '<p class="card-text">' . $row['order_date'] . '</p>';

        echo '<p class="card-text">' . ucfirst($row['status']) . '</p>';
        echo '<h5 class="card-text">$' . $row['order_total'] . '</h5>';
        echo '<a href="../profile/order.php?order_nb=' . $row['order_nb'] . '"style="display:flex;align-items:center;"><span>More Details</span> <span class="material-symbols-outlined" style="padding-right 5px; font-size:20px">arrow_forward</span></a>';

        if ($row['status'] != 'delivered' && $row['status'] != 'cancelled') {
            echo '<button class="cancel-button" data-id="' . $row['order_nb'] . '"><span class="material-symbols-outlined">close_small</span></button>';
        }
        echo '</div></div>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<script>
    $('.cancel-button').click(function() {
        // Get the order number
        var orderNb = $(this).data('id');
        // Define the callback function
        var callback = function() {
            $.ajax({
                url: 'users/cancel_order.php',
                type: 'POST',
                data: {
                    order_nb: orderNb
                },
                success: function(data) {
                    // hideConfirmationModal();
                    showResponseModal("Order has been cancelled successfully!", function() {
                        // user orders refresh
                        var userId = <?= $user_id ?>;

                        function loadContent(url) {
                            $.ajax({
                                url: url,
                                type: 'GET',
                                success: function(response) {
                                    $('#main-content').html(response);
                                },
                                error: function(xhr, status, error) {
                                    console.log(error);
                                }
                            });
                        }
                        loadContent('users/user_orders.php?user_id=' + userId);

                    })
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                    console.error(textStatus, errorThrown);
                }
            });
        };

        // Show the confirmation modal
        showConfirmationModal('Are you sure you want to cancel this order?', callback);
    });
</script>