<?php
session_start();
require_once('connector.php');

$prodName = $_POST['addname'];
$prodPrice = $_POST['addprice'];
$prodDesc = $_POST['adddescription'];
$prodQty = $_POST['addquantity'];
$prodCreated = $_POST['adddate_created'];

// Handle File Upload
$target_dir = "uploads/"; // Define the folder first

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true); // Creates the folder if it doesn't exist
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a valid image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if ($check === false) {
    echo "<script>alert('File is not an image.');history.back();</script>";
    exit;
}

// Allow only certain file formats
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($imageFileType, $allowed_types)) {
    echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.');history.back();</script>";
    exit;
}

// Move uploaded file to target directory
if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "<script>alert('Sorry, there was an error uploading your file.');history.back();</script>";
    exit;
}

// Save the file path in the database
$prodImage = $target_file;

// Check if product exists
$stmt = $dbconn->prepare('SELECT * FROM products WHERE name = ?');
$stmt->bind_param('s', $prodName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->fetch_assoc()) {
    echo "<script>alert('Product already exists.');history.back();</script>";
    exit;
}

// Insert product into the database
$stmt2 = $dbconn->prepare('INSERT INTO products (name, price, image, description, quantity, date_created) VALUES (?, ?, ?, ?, ?, ?)');
$stmt2->bind_param('sdssis', $prodName, $prodPrice, $prodImage, $prodDesc, $prodQty, $prodCreated);
if ($stmt2->execute()) {
    echo "<script>alert('Product added successfully.'); location.href='manage_products.php';</script>";
} else {
    echo "<script>alert('Error adding product.'); history.back();</script>";
}
?>
