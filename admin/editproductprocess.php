<?php
session_start();
require_once('connector.php');

$prodID = $_POST['editID'];
$prodName = $_POST['editname'];
$prodPrice = $_POST['editprice'];
$prodDesc = $_POST['editdescription'];
$prodQty = $_POST['editquantity'];
$prodCreated = $_POST['editdate_created'];

$target_dir = "uploads/"; // Folder for images

// Ensure the uploads directory exists
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

// Fetch existing product details
$stmt = $dbconn->prepare('SELECT * FROM products WHERE id = ?');
$stmt->bind_param('s', $prodID);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_assoc();

if ($rows) {
    $prodImage = $rows['image']; // Default to existing image

    // Check if a new image is uploaded
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check for upload errors
        if ($_FILES["fileToUpload"]["error"] !== UPLOAD_ERR_OK) {
            echo "<script>alert('File upload error: " . $_FILES["fileToUpload"]["error"] . "'); history.back();</script>";
            exit;
        }

        // Validate the image file
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File is not an image.'); history.back();</script>";
            exit;
        }

        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.'); history.back();</script>";
            exit;
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>alert('Error moving uploaded file.'); history.back();</script>";
            exit;
        }

        $prodImage = $target_file; // Update image path
    }

    // Update product in database
    $stmt2 = $dbconn->prepare('UPDATE products SET name = ?, price = ?, image = ?, description = ?, quantity = ?, date_created = ? WHERE id = ?');
    $stmt2->bind_param('sdssisi', $prodName, $prodPrice, $prodImage, $prodDesc, $prodQty, $prodCreated, $prodID);

    if ($stmt2->execute()) {
        echo "<script>alert('Product updated successfully.'); location.href='viewproductpage.php';</script>";
    } else {
        echo "<script>alert('Error updating product.'); history.back();</script>";
    }
} else {
    echo "<script>alert('Product ID incorrect.'); location.href='viewproductpage.php';</script>";
}
?>
