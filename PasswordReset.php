<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Secure App System 2.0 : Password Reset</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System 2.0 : Password Reset</h1>
		<h2>To reset your password enter the details below.<h2>
		<h3>You can request a reset token by following the button 'Request Token' Button below.</h3>
		<?php		
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 1:
						echo "<h3>Email Address not found.</h3>";
						break;
					case 2:
						echo "<h3>Incorrect Date of birth.</h3>";
						break;
					case 3:
						echo "<h3>Password format was not correct.</h3>";
						break;
					case 4:
						echo "<h3>Reset Token has expired.</h3>";
						break;
					case 5:
						echo "<h3>Reset Token was not found.</h3>";
						break;
					case 6:
						echo "<h3>Passwords did not match.</h3>";
						break;
					case 7:
						$reset_tok = $_SESSION["reset_tok"];
						echo "<h3>This token is valid for 5 minutes:<br> <i>$reset_tok</i></h3>";
						break;
				}
			}
		?>  
		<form name= "resetForm" action="resetCheck.php" method="POST">
			<label><b>Email</b></label>
			<input type="email" name="email" placeholder="Enter Email" required><br><br>
	
			<label><b>Date Of Birth</b></label>
			<input type="date" name="dob" required><br><br>
			
			<label><b>Reset Token</b></label>
			<input type="text" name="token" placeholder="Enter Token" required><br><br>
			
			<label><b>New Password</b></label>
			<input type="password" name="newPassword" placeholder="Enter New Password" required><br><br>
			
			<label><b>Repeat New Password</b></label>
			<input type="password" name="repeatPassword" placeholder="Enter New Password Again" required><br><br>
			<input type="submit" name="submit" value="Reset">
			
			<a href = "/index.php">
			<input type="button" value="Home">
			</a>
		</form>
		
		<br>
		<form>
			<a href = "/RequestToken.php">
			<input type="button" value="Request Token">
			</a>
		</form>
			
	</body>
</html>


<?php
    unset($_SESSION["errorCode"]);
	unset($_SESSION["reset_tok"]);
?>