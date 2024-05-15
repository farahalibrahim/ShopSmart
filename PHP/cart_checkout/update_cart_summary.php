<?php
// session_start();
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : $_COOKIE["user_id"];
function updateCartSummary($conn, $user_id)
{
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : $user_id;
    $discount_percent = isset($_POST['discount']) ? $_POST['discount'] : 0; // Retrieve the discount from the AJAX request

    $sql = 'SELECT s.name, SUM(p.price * c.quantity) as total
               FROM cart c
               JOIN product p ON c.product_barcode = p.barcode AND c.supermarket_id = p.supermarket_id
               JOIN supermarket s ON c.supermarket_id = s.id
               WHERE c.user_id = :user_id
               GROUP BY c.supermarket_id, s.name;';
    $stmt = DatabaseHelper::runQuery($conn, $sql, ['user_id' => $user_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = '';

    if (count($results) > 0) {
        $subtotal = 0;
        foreach ($results as $result) {
            $subtotal += $result['total'];
        }
        $output .= "<details>";
        $output .= "<summary>Subtotal $" . $subtotal . "</summary>";
        foreach ($results as $result) {
            $output .= "<summary>$result[name]: $$result[total]</summary>";
        }
        $output .= "</details>";
        // echo $discount_percent;
        $discount = 0;
        if ($discount_percent > 0) {
            $discount = $subtotal * $discount_percent / 100;
        }

        $output .= "<p style='display:block;'>Discount: $$discount</p>";
        $vat = number_format(($subtotal - $discount) * 0.11, 2);
        $output .= "<p>VAT(11%): $$vat</p>";
        $delivery = 5; //fixed delivery fee
        $output .= "<p>Delivery Fees: $$delivery</p>";
        $total = $subtotal - $discount + $vat + $delivery;
        $output .= "<p>Total: $$total</p>";
        $output .= "<input type='hidden' id='placeorder_total' value='$total'>";
    }

    return $output;
}

echo updateCartSummary($conn, $user_id);
