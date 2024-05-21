<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

$sql = "SELECT product_name, barcode FROM product WHERE product_name LIKE ? GROUP BY product_name LIMIT 4";
$query = $_POST['query'];
$stmt = DatabaseHelper::runQuery($conn, $sql, ["%$query%"]);

$results = $stmt->fetchAll();
if ($results) {
    // Display a row with the search contents
    echo '<div class="search-result"><a href="http://localhost:3000/PHP/search_results.php?search=' . $query . '">Search results for "' . $query . '"</a></div>';

    // Display the matches
    foreach ($results as $row) {
        $product_name = $row['product_name'];
        $display_name = mb_strimwidth($product_name, 0, 20, "..");
        echo '<div class="search-result"><a href="http://localhost:3000/PHP/search_results.php?search=' . $product_name . '">' . $display_name . '</a></div>';
    }
}
echo '<style>
.search-result {
    padding: 10px;
}
</style>';
