<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

if (isset($_POST['new_category'])) {
    $newCategory = $_POST['new_category'];

    try {
        $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

        // SQL to get current enum options
        $sql = "SELECT COLUMN_TYPE 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = :database 
                AND TABLE_NAME = :table 
                AND COLUMN_NAME = :column";

        $stmt = DatabaseHelper::runQuery($conn, $sql, ['database' => "senior", 'table' => 'product', 'column' => 'category']);

        $enumList = $stmt->fetch(PDO::FETCH_ASSOC);

        preg_match("/^enum\(\'(.*)\'\)$/", $enumList['COLUMN_TYPE'], $matches);
        $enumOptions = explode("','", $matches[1]);

        // Add new category to enum options
        $enumOptions[] = $newCategory;
        $enumOptionsString = "'" . implode("','", $enumOptions) . "'";

        // SQL to alter table
        $sql = "ALTER TABLE product MODIFY COLUMN category ENUM($enumOptionsString)";

        // Use exec() because no results are returned
        $conn->exec($sql);

        echo "Category added successfully";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}
