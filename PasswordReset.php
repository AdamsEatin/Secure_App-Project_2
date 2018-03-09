<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<script>
		function validatePassword(){
			var regexCheck = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
			var pass = document.forms["regForm"]["password"].value;
			if(regexCheck.test(pass)){
				return true;
			}else{
				alert("Passwords must; \n-Contain 1 uppercase letter \n-Contain 1 lower case letter \n-Contain 1 numeric character \n-Be at least 8 characters long")
				return false;
			}
		}
		</script>
		
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
					case 8:
						echo "<h3>Passed</h3>";
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
			<input type="submit" name="submit" value="Reset" onclick="return validatePassword()">
			
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