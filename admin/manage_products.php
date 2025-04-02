<?php
session_start();
require_once 'class.user.php';
require_once 'connector.php';
$user_home = new USER();

if (!$user_home->is_logged_in()) {
    $user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM admin WHERE userID=:uid");
$stmt->execute(array(":uid" => $_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Pagination settings
$limit = 10; // Products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = mysqli_real_escape_string($dbconn, $_GET['search']);
    
    // Search by ID, Name
    $query = "SELECT * FROM products 
              WHERE id LIKE '%$search_query%' 
              OR name LIKE '%$search_query%' 
              LIMIT $limit OFFSET $offset";
              
    $total_query = "SELECT COUNT(*) as total FROM products 
                    WHERE id LIKE '%$search_query%' 
                    OR name LIKE '%$search_query%'";
} else {
    $query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
    $total_query = "SELECT COUNT(*) as total FROM products";
}

$results = mysqli_query($dbconn, $query);
$total_results = mysqli_fetch_assoc(mysqli_query($dbconn, $total_query))['total'];
$total_pages = ceil($total_results / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    
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
                <h1 class="mt-3">Manage Products</h1>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="add_product.php" method="post">
                            <input type="submit" class="btn btn-primary" value="Add Product">
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="" method="GET" class="d-flex">
                            <input type="text" class="form-control me-2" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
                <hr>
                
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($results->num_rows > 0) {
                            while ($row = mysqli_fetch_array($results)) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['name']}</td>
                                        <td>{$row['date_created']}</td>
                                        <td>
                                            <form method='POST' action='editproductpage.php' class='d-inline'>
                                                <input type='hidden' name='PNAME' value='{$row['id']}' />
                                                <input type='submit' class='btn btn-warning' value='Edit'>
                                            </form>
                                            <form method='POST' action='delproductprocess.php' class='d-inline'>
                                                <input type='hidden' name='PNAME' value='{$row['id']}' />
                                                <input type='submit' class='btn btn-danger' value='Delete'>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No products found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1&search=<?php echo $search_query; ?>">First</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search_query; ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search_query; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search_query; ?>">Next</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $total_pages; ?>&search=<?php echo $search_query; ?>">Last</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
