<?php
require_once 'dbconfig.php';

class USER
{

	private $conn;

	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}

	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}

	public function register($uname, $email, $upass, $code)
	{
		try {
			// Use bcrypt to hash the password
			$password = password_hash($upass, PASSWORD_BCRYPT);  // More secure than MD5

			$stmt = $this->conn->prepare("INSERT INTO admin(userName, userEmail, userPass, tokenCode)
										  VALUES(:user_name, :user_mail, :user_pass, :active_code)");

			$stmt->bindparam(":user_name", $uname);
			$stmt->bindparam(":user_mail", $email);
			$stmt->bindparam(":user_pass", $password);
			$stmt->bindparam(":active_code", $code);
			$stmt->execute();
			return $stmt;
		} catch (PDOException $ex) {
			echo $ex->getMessage();
		}
	}


	public function login($email, $upass)
	{
		try {
			// Prepare the query
			$stmt = $this->conn->prepare("SELECT * FROM admin WHERE userEmail=:email_id");

			// Execute the query
			$stmt->execute(array(":email_id" => $email));

			// Fetch the user data
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($stmt->rowCount() == 1) {
				// Verify the entered password
				if (password_verify($upass, $userRow['userPass'])) {
					echo "Password is correct!<br>";

					// Store session and return true
					$_SESSION['userSession'] = $userRow['userID'];
					return true;
				} else {
					echo "Password is incorrect!<br>";
					header("Location: index.php?error");
					exit;
				}
			} else {
				echo "No matching user found with the given email.<br>";
				header("Location: index.php?error");
				exit;
			}
		} catch (PDOException $ex) {
			echo $ex->getMessage();
		}
	}



	public function is_logged_in()
	{
		if (isset($_SESSION['userSession'])) {
			return true;
		}
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}

	public function send_mail($email, $message, $subject)
	{
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 587;
		$mail->AddAddress($email);
		$mail->Username = "";
		$mail->Password = "";  // Please use app password for production
		$mail->SetFrom('', '');
		$mail->AddReplyTo("", "");
		$mail->Subject = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}
}
?>