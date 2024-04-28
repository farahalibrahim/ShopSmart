<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$type = $_GET['type'];
$query = $_GET['query'];



if (!empty($query)) {
    switch ($type) {
        case 'product-barcode':
            $sql = "SELECT DISTINCT barcode, product_name FROM Product WHERE barcode LIKE ?";
            $params = ['%' . $query . '%'];
            break;
        case 'product-name':
            $sql = "SELECT DISTINCT barcode, product_name FROM Product WHERE product_name LIKE ?";
            $params = ['%' . $query . '%'];
            break;
        case 'product-manuf':
            $sql = "SELECT DISTINCT manufacturer FROM Product WHERE manufacturer LIKE ?";
            $params = ['%' . $query . '%'];
            break;
    }
    $stmt = DatabaseHelper::runQuery($conn, $sql, $params);

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as a JSON response
    echo json_encode($results);
} else {
    echo json_encode([]);
}
