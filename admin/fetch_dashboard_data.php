<?php
header("Content-Type: application/json");
include 'connector.php'; // Ensure this file establishes $dbconn properly

$response = ["status" => "error", "message" => "Failed to fetch dashboard data", "data" => []];

// Fetch total products
$productQuery = "SELECT COUNT(*) AS total_products FROM products";
$adminQuery = "SELECT COUNT(*) AS total_admin FROM admin";
$productResult = mysqli_query($dbconn, $productQuery);
$totalProducts = $productResult ? mysqli_fetch_assoc($productResult)['total_products'] : 0;
$adminResult = mysqli_query($dbconn, $adminQuery);
$totaladmin = $adminResult ? mysqli_fetch_assoc($adminResult)['total_admin'] : 0;

// Fetch total pending orders
$pendingOrderQuery = "SELECT COUNT(*) AS total_pending_orders FROM orders WHERE order_status = 'Pending'";
$pendingOrderResult = mysqli_query($dbconn, $pendingOrderQuery);
$totalPendingOrders = $pendingOrderResult ? mysqli_fetch_assoc($pendingOrderResult)['total_pending_orders'] : 0; // Fix: Changed $orderResult to $pendingOrderResult

// Fetch total completed orders
$completedOrderQuery = "SELECT COUNT(*) AS total_completed_orders FROM orders WHERE order_status = 'Completed'";
$completedOrderResult = mysqli_query($dbconn, $completedOrderQuery);
$totalCompletedOrders = $completedOrderResult ? mysqli_fetch_assoc($completedOrderResult)['total_completed_orders'] : 0;

// Fetch total revenue (sum of total_amount column in orders)
$revenueQuery = "SELECT SUM(total_amount) AS total_revenue FROM orders WHERE payment_status = 'Completed'";
$revenueResult = mysqli_query($dbconn, $revenueQuery);
$totalRevenue = $revenueResult ? mysqli_fetch_assoc($revenueResult)['total_revenue'] : 0;

// Construct response
$response["status"] = "success";
$response["message"] = "Dashboard data retrieved successfully";
$response["data"] = [
    "total_products" => $totalProducts,
    "total_admin" => $totaladmin,
    "total_pending_orders" => $totalPendingOrders,
    "total_completed_orders" => $totalCompletedOrders,
    "total_revenue" => $totalRevenue,
];

// Close database connection
mysqli_close($dbconn);

// Output JSON response
echo json_encode($response);
?>