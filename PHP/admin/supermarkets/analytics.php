<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
    header('Content-Type: application/json');

    $salesAnalytics = $_POST['sales_analytics'];
    $analysisDuration = $_POST['analysis_duration'];

    // Convert analysisDuration to a number
    $analysisDuration = str_replace(['current_month', '3_months', '6_months', '1_year'], [1, 3, 6, 12], $analysisDuration);

    switch ($salesAnalytics) {
        case 'total_sales':
            $stmt = DatabaseHelper::runQuery($conn, "SELECT supermarket.name, SUM(order_total) as total_sales FROM `order_details` INNER JOIN supermarket ON `order_details`.supermarket_id = supermarket.id INNER JOIN `order` ON `order_details`.order_nb = `order`.order_nb WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL :analysisDuration MONTH) GROUP BY supermarket.name;", ['analysisDuration' => $analysisDuration]);
            break;
        case 'top_selling_products':
            $stmt = DatabaseHelper::runQuery($conn, "SELECT supermarket.name, product.product_name, product.manufacturer, SUM(order_details.quantity) as quantity FROM order_details INNER JOIN `order` ON order_details.order_nb = `order`.order_nb INNER JOIN supermarket ON order_details.supermarket_id = supermarket.id INNER JOIN product ON order_details.product_barcode = product.barcode WHERE `order`.order_date >= DATE_SUB(CURDATE(), INTERVAL :analysisDuration MONTH) GROUP BY supermarket.name, product.product_name, product.manufacturer ORDER BY quantity DESC;", ['analysisDuration' => $analysisDuration]);
            break;
        case 'number_of_orders':
            $stmt = DatabaseHelper::runQuery($conn, "SELECT supermarket.name, COUNT(*) as number_of_orders FROM `order_details` INNER JOIN supermarket ON `order_details`.supermarket_id = supermarket.id INNER JOIN `order` ON `order_details`.order_nb = `order`.order_nb WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL :analysisDuration MONTH) GROUP BY supermarket.name", ['analysisDuration' => $analysisDuration]);
            break;
        case 'average_order_value':
            $stmt = DatabaseHelper::runQuery($conn, "SELECT supermarket.name, SUM(order_details.quantity*order_details.price) as average_order_value FROM `order_details` INNER JOIN supermarket ON `order_details`.supermarket_id = supermarket.id INNER JOIN `order` ON `order_details`.`order_nb` = `order`.`order_nb` WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL :analysisDuration MONTH) GROUP BY supermarket.name;", ['analysisDuration' => $analysisDuration]);
            break;
    }

    // Fetch results and output as JSON
    $results = $stmt->fetchAll();
    echo json_encode($results);
} catch (Exception $e) {
    // Output error message as JSON
    echo json_encode(['error' => $e->getMessage()]);
}
