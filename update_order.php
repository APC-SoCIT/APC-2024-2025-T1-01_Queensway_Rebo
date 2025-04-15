<?php
require_once 'connector.php';

// Retrieve the JSON data sent from the frontend
$inputData = file_get_contents("php://input");
$data = json_decode($inputData, true);

// Validate the input data
if (!$data || !isset($data['orderID']) || !isset($data['totalAmount']) || !isset($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

// Prepare data from PayPal response
$orderID = $data['orderID'];
$totalAmount = $data['totalAmount'];
$items = $data['items'];

// Assuming user is logged in and we have a session with user ID (replace 1 with the actual logged-in user's ID)
session_start();
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;  // Default to 1 if no user session is found
$orderStatus = 'Pending';  // Default status, you can change it as needed
$paymentStatus = 'Completed';  // You can update this based on PayPal's response

// Begin transaction
mysqli_begin_transaction($dbconn);

try {
    // Insert the order into the `orders` table using a prepared statement
    $orderQuery = "INSERT INTO orders (user_id, total_amount, order_status, payment_status, paypal_order_id, created_at) 
                   VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($dbconn, $orderQuery);
    if (!$stmt) {
        throw new Exception('Error preparing order query: ' . mysqli_error($dbconn));
    }

    mysqli_stmt_bind_param($stmt, 'idsss', $userID, $totalAmount, $orderStatus, $paymentStatus, $orderID);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error inserting order: ' . mysqli_error($dbconn));
    }

    // Get the last inserted order ID
    $orderIDInserted = mysqli_insert_id($dbconn);

    // Insert order items into the `order_items` table using prepared statements
    foreach ($items as $item) {
        $productID = $item['id'];
        $productName = $item['name'];
        $quantity = $item['quantity'];
        $unitPrice = $item['price'];

        $orderItemsQuery = "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price) 
                            VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($dbconn, $orderItemsQuery);
        if (!$stmt) {
            throw new Exception('Error preparing order item query: ' . mysqli_error($dbconn));
        }

        mysqli_stmt_bind_param($stmt, 'iisdi', $orderIDInserted, $productID, $productName, $quantity, $unitPrice);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Error inserting order item: ' . mysqli_error($dbconn));
        }

        // Update the product stock in the `products` table using a prepared statement
        $updateStockQuery = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
        $stmt = mysqli_prepare($dbconn, $updateStockQuery);
        if (!$stmt) {
            throw new Exception('Error preparing update stock query: ' . mysqli_error($dbconn));
        }

        mysqli_stmt_bind_param($stmt, 'ii', $quantity, $productID);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Error updating product stock: ' . mysqli_error($dbconn));
        }
    }

    // Commit the transaction
    mysqli_commit($dbconn);


    // Clear the cart session after order is successfully processed
    unset($_SESSION['cart']);

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Order processed successfully.']);
} catch (Exception $e) {
    // Rollback the transaction on error
    mysqli_rollback($dbconn);

    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>