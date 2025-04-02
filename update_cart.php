<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request.'];

if (isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) {
        $response['message'] = 'Quantity must be at least 1.';
    } elseif (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
        $subtotal = $_SESSION['cart'][$productId]['price'] * $quantity;
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $_SESSION['cart']));

        $response = [
            'success' => true,
            'subtotal' => $subtotal,
            'total' => $total
        ];
    } else {
        $response['message'] = 'Product not found in cart.';
    }
}

echo json_encode($response);
?>
