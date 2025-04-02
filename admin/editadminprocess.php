<?php

	session_start();
	require_once('connector.php');


	$prodID=$_POST['editID'];
	$ffname=$_POST['firstname1'];
	$llname=$_POST['lastname'];
	$uusername=$_POST['username'];
	$emailadd=$_POST['emailadd'];


	$stmt = $dbconn->prepare('SELECT * FROM admin WHERE userID = ?');
	$stmt->bind_param('i', $prodID);
	$stmt->execute();
	$result = $stmt->get_result();
	if($rows = $result->fetch_assoc()) {
		$stmt2 = $dbconn->prepare('UPDATE admin SET FirstName = ?, LastName = ?, userName= ?, userEmail = ? WHERE userID = ?');
		$stmt2->bind_param('ssssi', $ffname, $llname, $uusername, $emailadd, $prodID);
		$stmt2->execute();

		echo"<script>window.alert('Admin updated.');</script>";
		echo"<script>location.href='viewadminpage.php';</script>";
	} else {
		echo"<script>window.alert('Admin incorrect');</script>";
		echo"<script>location.href='viewadminpage.php';</script>";
	}

?>
