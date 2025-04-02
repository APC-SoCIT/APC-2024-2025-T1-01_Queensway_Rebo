     <?php
	session_start();
	require_once('connector.php');

	$prodID=$_POST['PNAME'];

	$stmt = $dbconn->prepare('SELECT * FROM products WHERE id = ?');
	$stmt->bind_param('i', $prodID);
	$stmt->execute();
	$result = $stmt->get_result();
	if($rows = $result->fetch_assoc()) {
		$stmt2 = $dbconn->prepare('DELETE FROM products WHERE id = ?');
		$stmt2->bind_param('i', $prodID);
		$stmt2->execute();

		echo"<script>window.alert('Product deleted.');</script>";
		echo"<script>location.href='viewproductpage.php';</script>";
	} else {
		echo"<script>window.alert('Product Code incorrect');</script>";
		echo"<script>location.href='viewproductpage.php';</script>";
	}
?>