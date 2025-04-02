<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request.'];

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $_SESSION['cart']));

        $response = [
            'success' => true,
            'total' => $total
        ];
    } else {
        $response['message'] = 'Product not found in cart.';
    }
}

echo json_encode($response);
?>
