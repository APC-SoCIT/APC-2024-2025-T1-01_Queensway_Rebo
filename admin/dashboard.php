<?php
session_start();
require_once 'class.user.php';
$user_home = new USER();

if (!$user_home->is_logged_in()) {
    $user_home->redirect('index.php');
}

if (!isset($_SESSION['userSession'])) {
    die("Error: User session is not set.");
}

$stmt = $user_home->runQuery("SELECT * FROM admin WHERE userID=:uid");
$stmt->execute(array(":uid" => $_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Store user email in session to avoid re-querying the database
$_SESSION['userEmail'] = $row ? $row['userEmail'] : 'Unknown User';

$user = new USER();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container-fluid mt-5">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h1 class="mt-3">Admin Dashboard</h1>
                <div class="row">
                    <!-- Total Admins Card -->
                    <div class="col-md-3">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body text-center">
                                <h5>Total Admins</h5>
                                <h1 id="adminCount">0</h1>
                            </div>
                        </div>
                    </div>
                    <!-- Total Products Card -->
                    <div class="col-md-3">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body text-center">
                                <h5>Total Products</h5>
                                <h1 id="productCount">0</h1>
                            </div>
                        </div>
                    </div>
                    <!-- Pending Orders Card -->
                    <div class="col-md-3">
                        <div class="card bg-warning text-white mb-3">
                            <div class="card-body text-center">
                                <h5>Pending Orders</h5>
                                <h1 id="pendingOrders">0</h1>
                            </div>
                        </div>
                    </div>
                    <!-- Completed Orders Card -->
                    <div class="col-md-3">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body text-center">
                                <h5>Completed Orders</h5>
                                <h1 id="completedOrders">0</h1>
                            </div>
                        </div>
                    </div>
                    <!-- Total Revenue Card (Updated to bg-dark) -->
                    <div class="col-md-3">
                        <div class="card bg-dark text-white mb-3">
                            <div class="card-body text-center">
                                <h5>Total Revenue</h5>
                                <h1 id="totalRevenue">₱0.00</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("fetch_dashboard_data.php")
                .then(response => response.json())
                .then(data => {
                    // Update the displayed values on the dashboard
                    document.getElementById("adminCount").textContent = data.data.total_admin;
                    document.getElementById("productCount").textContent = data.data.total_products;
                    document.getElementById("pendingOrders").textContent = data.data.total_pending_orders;
                    document.getElementById("completedOrders").textContent = data.data.total_completed_orders;

                    // Format revenue with commas
                    const formattedRevenue = parseFloat(data.data.total_revenue).toLocaleString();
                    document.getElementById("totalRevenue").textContent = '₱' + formattedRevenue;
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
</body>

</html>