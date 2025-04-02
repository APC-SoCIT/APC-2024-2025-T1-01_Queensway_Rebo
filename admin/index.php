<?php
session_start();
require_once 'class.user.php';
$user_login = new USER();

if($user_login->is_logged_in()!="")
{
	$user_login->redirect('dashboard.php');
}

if(isset($_POST['btn-login']))
{
	$email = trim($_POST['txtemail']);
	$upass = trim($_POST['txtupass']);
	
	if($user_login->login($email,$upass))
	{
		$user_login->redirect('dashboard.php');
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Llanes Farm Admin</title>

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
        padding: 80px;
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
        <h2 class="text-center mb-4">Log In</h2>

        <!-- Alert for inactive account -->
        <?php 
        if(isset($_GET['inactive']))
        {
        ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Sorry!</strong> This Account is not Activated. Go to your Inbox and Activate it.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        }
        ?>

        <!-- Alert for wrong login details -->
        <?php 
        if(isset($_GET['error']))
        {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Wrong Details!</strong> Please check your email and password.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        }
        ?>

        <!-- Login Form -->
        <form method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="txtemail" required />
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="txtupass" required />
          </div>

          <div class="d-flex justify-content-between">
            <button type="submit" name="btn-login" class="btn btn-primary">Submit</button>
            <a href="signup.php" class="btn btn-link">Sign Up</a>
          </div>

          <hr />
          <a href="fpass.php" class="btn btn-link w-100">Lost your Password?</a>
        </form>
      </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
