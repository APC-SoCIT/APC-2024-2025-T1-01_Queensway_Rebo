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
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="mb-4 mt-3">Add Product</h1>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="addproductprocess.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label" for="addname">Product Name</label>
                            <input type="text" class="form-control" id="addname" name="addname" required maxlength="50">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="addprice">Price</label>
                            <input type="number" class="form-control" id="addprice" name="addprice" required step="0.01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="addquantity">Quantity</label>
                            <input type="number" class="form-control" id="addquantity" name="addquantity" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="adddate_created">Date Created</label>
                            <input type="date" class="form-control" id="adddate_created" name="adddate_created" value="<?php echo date('Y-m-d'); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="fileToUpload">Image</label>
                            <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="adddescription">Description</label>
                            <textarea class="form-control" id="adddescription" name="adddescription" rows="4" maxlength="500"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
