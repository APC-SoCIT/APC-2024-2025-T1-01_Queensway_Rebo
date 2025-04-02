<?php
session_start();


// Fetch cart items from session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate total
$total = 0;
foreach ($cartItems as $id => $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - YourStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            width: 100px;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">YourStore</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Shopping Cart Section -->
    <div class="container my-5">
        <h1 class="mb-4">Your Shopping Cart</h1>
        <?php if (!empty($cartItems)): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $id => $item): ?>
                            <tr id="cart-item-<?php echo $id; ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="admin/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img me-3">
                                        <div>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        </div>
                                    </div>
                                </td>
                                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" class="form-control quantity-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo htmlspecialchars($item['stock']); ?>" data-product-id="<?php echo $id; ?>" required>
                                </td>
                                <td id="subtotal-<?php echo $id; ?>">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-item" data-product-id="<?php echo $id; ?>">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td id="total-amount">₱<?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-between">
                <a href="shop.php" class="btn btn-outline-secondary">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p class="text-muted">Your cart is empty.</p>
            <a href="shop.php" class="btn btn-primary">Start Shopping</a>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 YourStore. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for handling quantity updates and item removal -->
    <script>
    
    document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity changes
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.getAttribute('data-product-id');
            const newQuantity = parseInt(this.value);

            if (newQuantity < 1) {
                alert('Quantity must be at least 1.');
                this.value = 1;
                return;
            }

            updateCart(productId, newQuantity);
        });
    });

    // Handle item removal
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            removeFromCart(productId);
        });
    });

    // Function to update cart
    function updateCart(productId, quantity) {
        fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`subtotal-${productId}`).textContent = `₱${data.subtotal.toFixed(2)}`;
                document.getElementById('total-amount').textContent = `₱${data.total.toFixed(2)}`;
            } else {
                alert('Failed to update the cart. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Function to remove item from cart
    function removeFromCart(productId) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the item's row from the table
                document.getElementById(`cart-item-${productId}`).remove();
                // Update the total amount
                document.getElementById('total-amount').textContent = `₱${data.total.toFixed(2)}`;
                // If the cart is empty, display the empty cart message
                if (data.total === 0) {
                    document.querySelector('.table-responsive').innerHTML = '<p class="text-muted">Your cart is empty.</p><a href="shop.php" class="btn btn-primary">Start Shopping</a>';
                }
            } else {
                alert('Failed to remove the item. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

</script>

 
