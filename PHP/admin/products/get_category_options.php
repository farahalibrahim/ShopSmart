<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';
try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    // SQL to get enum options
    $sql = "SELECT COLUMN_TYPE 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = :database 
            AND TABLE_NAME = :table 
            AND COLUMN_NAME = :column";

    $stmt = DatabaseHelper::runQuery($conn, $sql, ['database' => "senior", 'table' => 'product', 'column' => 'category']);

    $enumList = $stmt->fetch(PDO::FETCH_ASSOC);

    preg_match("/^enum\(\'(.*)\'\)$/", $enumList['COLUMN_TYPE'], $matches);
    $enumOptions = explode("','", $matches[1]);

    // Create select options
    foreach ($enumOptions as $option) {
        echo "<option value=\"$option\">$option</option>";
    }
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
