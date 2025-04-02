<?php

	session_start();
	require_once('connector.php');


	$passid=$_SESSION['userSession'];
	$newpassword=$_POST['newpassword'];
	$newpassword2=$_POST['newpassword2'];


	$stmt = $dbconn->prepare('SELECT * FROM admin WHERE userID = ?');
	$stmt->bind_param('s', $passid);
	$stmt->execute();
	$result = $stmt->get_result();


	if($newpassword !=$newpassword2)
	{

		echo"<script>window.alert('Email not match');</script>";
		echo"<script>location.href='changepass.php';</script>";
	}
	else if
	 ($rows = $result->fetch_assoc()) {
		$stmt2 = $dbconn->prepare('UPDATE admin SET userPass = ? WHERE userID = ?');
		$stmt2->bind_param('si', $newpassword2, $passid);
		$stmt2->execute();

		echo"<script>window.alert('Password updated.');</script>";
		echo"<script>location.href='home.php';</script>";
}
 else {
		echo"<script>window.alert('Product Code incorrect');</script>";
		echo"<script>location.href='home.php';</script>";
	}


?>
