<?php

	session_start();
	require_once('connector.php');


	$wew=$_POST['addfirst'];
	$wew1=$_POST['addlast'];
	$username1=$_POST['username'];
	$userstatus1=$_POST['userstatus'];
	$email1=$_POST['emailaddress'];

	$stmt = $dbconn->prepare('SELECT * FROM admin WHERE userID = ?');
	$stmt->bind_param('i', $adminID);
	$stmt->execute();
	$result = $stmt->get_result();
	if($rows = $result->fetch_assoc()) {
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>alert('Admin already exists.');history.back();</script>";
		$stmt->close();
		exit;
	} else {
		$stmt2 = $dbconn->prepare('INSERT INTO admin (userID, FirstName, LastName, userStatus, userName, userEmail) VALUES (?, ?, ?, ?, ?, ?)');
		$stmt2->bind_param('isssss', $adminID, $wew, $wew1, $userstatus1, $username1, $email1);
		$stmt2->execute();
		echo"<script>window.alert('Product added.');</script>";
		echo"<script>location.href='viewadminpage.php';</script>";
	}

?>
