<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if ($product_id === false || $quantity === false || $quantity <= 0) {
        die('Invalid product ID or quantity.');
    }

    require_once 'connector.php';

    $query = "SELECT id, name, price, quantity, image FROM products WHERE id = ?";
    $stmt = $dbconn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die('Product not found.');
    }

    if ($quantity > $product['quantity']) {
        die('Requested quantity exceeds available stock.');
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'stock' => $product['quantity'],
            'image' => $product['image'],
            'id' => $product_id
        ];

    }

    header('Location: cart.php');
    exit();
} else {
    die('Invalid request.');
}
?>