<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    // Prepare and execute the queries
    $totalSalesStmt = DatabaseHelper::runQuery($conn, "SELECT supermarket.name AS supermarket_name, SUM(order_details.price * order_details.quantity) AS total_sales FROM order_details INNER JOIN supermarket ON order_details.supermarket_id = supermarket.id GROUP BY supermarket.name");    // $totalSalesStmt->execute();
    $totalSales = $totalSalesStmt->fetchAll(PDO::FETCH_ASSOC);

    $numberOfOrdersStmt = DatabaseHelper::runQuery($conn, "SELECT supermarket.name AS supermarket_name, COUNT(DISTINCT order_details.order_nb) AS number_of_orders FROM order_details INNER JOIN supermarket ON order_details.supermarket_id = supermarket.id GROUP BY supermarket.name");    // $numberOfOrdersStmt->execute();
    $numberOfOrders = $numberOfOrdersStmt->fetchAll(PDO::FETCH_ASSOC);

    $averageOrderValueStmt = DatabaseHelper::runQuery($conn, "SELECT supermarket.name AS supermarket_name, AVG(order_details.price * order_details.quantity) AS average_order_value FROM order_details INNER JOIN supermarket ON order_details.supermarket_id = supermarket.id GROUP BY supermarket.name");    // $averageOrderValueStmt->execute();
    $averageOrderValue = $averageOrderValueStmt->fetchAll(PDO::FETCH_ASSOC);

    $topSellingproductStmt = DatabaseHelper::runQuery($conn, "
    SELECT supermarket_name, product_name, manufacturer, total_quantity
    FROM (
        SELECT 
            supermarket.name AS supermarket_name, 
            product.product_name, 
            product.manufacturer, 
            SUM(order_details.quantity) AS total_quantity,
            ROW_NUMBER() OVER(PARTITION BY supermarket.name ORDER BY SUM(order_details.quantity) DESC) as rn
        FROM order_details 
        INNER JOIN supermarket ON order_details.supermarket_id = supermarket.id 
        INNER JOIN product ON order_details.product_barcode = product.barcode 
        GROUP BY supermarket.name, product.product_name, product.manufacturer
    ) t
    WHERE rn <= 3
    ORDER BY supermarket_name, total_quantity DESC
");
    $topSellingproduct = $topSellingproductStmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as a JSON object
    echo json_encode([
        'totalSales' => $totalSales,
        'numberOfOrders' => $numberOfOrders,
        'averageOrderValue' => $averageOrderValue,
        'topSellingproduct' => $topSellingproduct
    ]);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
