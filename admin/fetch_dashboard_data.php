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

// // Fetch total orders
// $orderQuery = "SELECT COUNT(*) AS total_orders FROM orders";
// $orderResult = mysqli_query($dbconn, $orderQuery);
// $totalOrders = $orderResult ? mysqli_fetch_assoc($orderResult)['total_orders'] : 0;

// // Fetch total revenue
// $revenueQuery = "SELECT SUM(total_price) AS total_revenue FROM orders WHERE order_status = 'Completed'";
// $revenueResult = mysqli_query($dbconn, $revenueQuery);
// $totalRevenue = $revenueResult ? mysqli_fetch_assoc($revenueResult)['total_revenue'] : 0;
// $totalRevenue = $totalRevenue ? $totalRevenue : 0;

// // Fetch recent orders (limit to 5)
// $recentOrdersQuery = "SELECT order_id, customer_name, total_price, order_status, created_at FROM orders ORDER BY created_at DESC LIMIT 5";
// $recentOrdersResult = mysqli_query($dbconn, $recentOrdersQuery);
// $recentOrders = [];
// if ($recentOrdersResult) {
//     while ($row = mysqli_fetch_assoc($recentOrdersResult)) {
//         $recentOrders[] = $row;
//     }
// }

// Construct response
$response["status"] = "success";
$response["message"] = "Dashboard data retrieved successfully";
$response["data"] = [
    "total_products" => $totalProducts,
    "total_admin" => $totaladmin,
    // "total_orders" => $totalOrders,
    // "total_revenue" => $totalRevenue,
    // "recent_orders" => $recentOrders
];

// Close database connection
mysqli_close($dbconn);

// Output JSON response
echo json_encode($response);
?>
