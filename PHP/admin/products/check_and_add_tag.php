<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

$newTag = $_POST['new_tag'];
$newTagTitle = $_POST['new_tag_title'];

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    // SQL to check if tag exists
    $sql = "SELECT * FROM product_tags WHERE tag = :tag";
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['tag' => $newTag]);

    if ($stmt->rowCount() > 0) {
        echo 'Tag already exists';
    } else {
        // SQL to add new tag
        $sql = "INSERT INTO product_tags (tag, tag_title) VALUES (:tag, :title)";
        $stmt = DatabaseHelper::runQuery($conn, $sql, ['tag' => $newTag, 'title' => $newTagTitle]);

        echo 'Tag added successfully';
    }
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
