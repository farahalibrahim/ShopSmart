<?php
include_once '../../connection.inc.php';
include_once '../../dbh.class.inc.php';

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

try {
    $conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

    $stmt = DatabaseHelper::runQuery(
        $conn,
        "UPDATE user SET name = :name, email = :email, phone = :phone, street_address = :street, city = :city WHERE id = :id",
        ['id' => $_POST['id'], 'name' => $_POST['name'], 'email' => $_POST['email'], 'phone' => $_POST['phone'], 'street' => validate($_POST['street']), 'city' => validate($_POST['city'])]
    );

    echo "User updated successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
