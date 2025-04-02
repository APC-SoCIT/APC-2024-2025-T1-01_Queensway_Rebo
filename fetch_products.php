<?php
include 'connector.php';

$query = "SELECT * FROM products";
$result = mysqli_query($dbconn, $query);

$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>
