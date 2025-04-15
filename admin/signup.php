<?php
session_start();
require_once 'class.user.php';

$reg_user = new USER();

if ($reg_user->is_logged_in() != "") {
	$reg_user->redirect('home.php');
}

if (isset($_POST['btn-signup'])) {
	$uname = trim($_POST['txtuname']);
	$email = trim($_POST['txtemail']);
	$upass = trim($_POST['txtpass']);

	// Check if the email already exists
	$stmt = $reg_user->runQuery("SELECT * FROM admin WHERE userEmail=:email_id");
	$stmt->execute(array(":email_id" => $email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($stmt->rowCount() > 0) {
		$msg = "
            <div class='alert alert-danger'>
                <button class='close' data-dismiss='alert'>&times;</button>
                <strong>Sorry!</strong> Email already exists, please try another one.
            </div>
        ";
	} else {
		// Generate a unique activation code
		$code = bin2hex(random_bytes(16));  // Safer alternative to md5(uniqid(rand()))

		if ($reg_user->register($uname, $email, $upass, $code)) {
			$id = $reg_user->lasdID();
			$key = base64_encode($id); // You can use base64, though ensure it's handled safely
			$id = $key;

			$message = "
                Hello $uname,
                <br /><br />
                Welcome to Admin!<br/>
                To complete your registration, please click the following link<br/>
                <br /><br />
                <a href='http://localhost/thesis/admin/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
                <br /><br />
                Thanks,
            ";

			$subject = "Confirm Registration";

			// Send confirmation email
			//$reg_user->send_mail($email, $message, $subject);

			$msg = "
                <div class='alert alert-success'>
                    <button class='close' data-dismiss='alert'>&times;</button>
                    <strong>Success!</strong> We've sent an email to $email.
                    Please click on the confirmation link in the email to create your account.
                </div>
            ";
		} else {
			$msg = "
                <div class='alert alert-danger'>
                    <button class='close' data-dismiss='alert'>&times;</button>
                    <strong>Sorry!</strong> Something went wrong. Please try again later.
                </div>
            ";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
	<!-- Font Awesome for icons -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

	<style>
		body {
			background-color: #f4f6f9;
		}

		.login-container {
			max-width: 400px;
			margin: auto;
			padding: 60px;
			background-color: white;
			border-radius: 8px;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		}

		.form-label {
			font-weight: 500;
		}

		.btn-link {
			text-decoration: none;
		}
	</style>
</head>

<body>
	<div class="container d-flex justify-content-center align-items-center min-vh-100">
		<div class="login-container">
			<h2 class="text-center mb-4">Sign Up</h2>

			<!-- Display Error/Success Messages -->
			<?php if (isset($msg))
				echo $msg; ?>

			<!-- Signup Form -->
			<form method="post">
				<div class="mb-3">
					<label for="username" class="form-label">Username</label>
					<input type="text" class="form-control" id="username" name="txtuname" required />
				</div>

				<div class="mb-3">
					<label for="email" class="form-label">Email Address</label>
					<input type="email" class="form-control" id="email" name="txtemail" required />
				</div>

				<div class="mb-3">
					<label for="password" class="form-label">Password</label>
					<input type="password" class="form-control" id="password" name="txtpass" required />
				</div>

				<div class="d-flex justify-content-between">
					<button type="submit" name="btn-signup" class="btn btn-primary">Sign Up</button>
					<a href="index.php" class="btn btn-link">Sign In</a>
				</div>
			</form>
		</div>
	</div>

	<!-- Bootstrap 5 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>