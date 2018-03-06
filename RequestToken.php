<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Secure App System 2.0 : Token Request</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System 2.0 : Token Request</h1>
		<h2>To request a password reset token enter your email below.<h2>
		<h3>You can use this reset token to reset your password by following the 'Password Reset' button below.</h3>
		<?php		
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 1:
						echo "<h3>Email Address not found.</h3>";
						break;
				}
			}
		?>  
		<form name= "tokenForm" action="TokenRequest.php" method="POST">
			<label><b>Email</b></label>
			<input type="email" name="email" placeholder="Enter Email" required><br><br>
			
			<input type="submit" name="submit" value="Request">
			<a href = "/Index.php">
			<input type="button" value="Home">
			</a>
		</form>
		
		<br>
		<form>
			<a href = "/PasswordReset.php">
			<input type="button" value="Password Reset">
			</a>
		</form>
			
	</body>
</html>


<?php
    unset($_SESSION["errorCode"]);
?>