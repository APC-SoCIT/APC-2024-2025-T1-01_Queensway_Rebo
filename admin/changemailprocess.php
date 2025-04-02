<?php

	session_start();
	require_once('connector.php');


	$emailid=$_SESSION['userSession'];
	$email1=$_POST['email'];
	$email2=$_POST['email1'];


	$stmt = $dbconn->prepare('SELECT * FROM admin WHERE userID = ?');
	$stmt->bind_param('s', $emailid);
	$stmt->execute();
	$result = $stmt->get_result();


	if($email1 !=$email2)
	{

		echo"<script>window.alert('Email not match');</script>";
		echo"<script>location.href='changemail.php';</script>";
	}
	else if
	 ($rows = $result->fetch_assoc()) {
		$stmt2 = $dbconn->prepare('UPDATE admin SET userEmail = ? WHERE userID = ?');
		$stmt2->bind_param('si', $email2, $emailid);
		$stmt2->execute();

		echo"<script>window.alert('Email updated.');</script>";
		echo"<script>location.href='home.php';</script>";
}
 else {
		echo"<script>window.alert('Product Code incorrect');</script>";
		echo"<script>location.href='home.php';</script>";
	}


?>
