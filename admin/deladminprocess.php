<?php
session_start();
require_once('connector.php');

$prodID=$_POST['UNAME'];

$stmt = $dbconn->prepare('SELECT * FROM admin WHERE userID = ?');
$stmt->bind_param('i', $prodID);
$stmt->execute();
$result = $stmt->get_result();
if($rows = $result->fetch_assoc()) {
$stmt2 = $dbconn->prepare('DELETE FROM admin WHERE userID = ?');
$stmt2->bind_param('i', $prodID);
$stmt2->execute();

echo"<script>window.alert('Product deleted.');</script>";
echo"<script>location.href='viewadminpage.php';</script>";
} else {
echo"<script>window.alert('Product Code incorrect');</script>";
echo"<script>location.href='viewadminpage.php';</script>";
}
?>
