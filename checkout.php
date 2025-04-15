<?php
session_start();

// Fetch cart items from session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate total
$total = 0;
foreach ($cartItems as $id => $item) {
    $total += $item['price'] * $item['quantity'];

}

// Convert cart items to a simple indexed array with necessary information for PayPal
$formattedCartItems = array_values(array_map(function ($item) {
    return [
        'id' => $item['id'],
        'name' => $item['name'],
        'price' => $item['price'],
        'quantity' => $item['quantity']
    ];
}, $cartItems));

// Pass the formatted cart items and total to JavaScript
echo '<script>var cartItems = ' . json_encode($formattedCartItems) . '; var totalAmount = ' . number_format($total, 2, '.', '') . ';</script>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - YourStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script
        src="https://www.paypal.com/sdk/js?client-id=AclKm4-7owCTrFj2yEIVlVIQJNXEbGX743w3s0xH1O3utxC8s3vJh1Za8alADJLTDkBIsi-q2_rXJQ58&currency=PHP"></script>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">YourStore</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <div class="container my-5">
        <h1 class="mb-4">Checkout</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Order Summary</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $id => $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total:</strong></td>
                            <td>₱<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-md-6">
                <!-- PayPal Button -->
                <div class="mt-5" id="paypal-button-container">
                    <h3>Or pay directly with PayPal</h3>
                    <div id="paypal-button-container"></div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 YourStore. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- PayPal Button Script -->
    <script>
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: "PHP",  // Set currency to PHP
                            value: '<?php echo number_format($total, 2, ".", ""); ?>' // Use PHP to pass the total value
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    const paypalPaymentId = details.id; // Get PayPal transaction ID
                    const custId = "123";  // Replace with actual customer ID (can be dynamic from session or user data)

                    // Prepare the order data to send to the backend
                    const orderData = {
                        orderID: paypalPaymentId, // PayPal Order ID
                        totalAmount: '<?php echo number_format($total, 2, ".", ""); ?>', // Total amount
                        items: <?php echo json_encode($formattedCartItems); ?> // Items array
                    };

                    console.log(orderData)

                    // Send order data to the backend for processing
                    fetch('update_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(orderData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Redirect to payment success page with the order ID
                                window.location.href = '/payment-success.php?order_id=' + paypalPaymentId;
                            } else {
                                // If the order processing failed, show an error
                                console.error('Error: ', data.message);
                                alert('Error processing payment. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Something went wrong. Please try again.');
                        });
                });
            },
            onError: function (err) {
                console.error("Payment failed with error:", err);
                alert('Payment failed. Please try again.');
            }
        }).render('#paypal-button-container');
    </script>

</body>

</html>