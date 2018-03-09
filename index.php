<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Secure App System 2.0 : Login </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System 2.0 : Login</h1>
		<h2>Please enter your details below to Login</h2>
		<?php
			session_start();	
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 0:
						echo "<h3>Failed to authenticate username and password at this time.<br><br>Lockout will occur after 5 failed attempts within 5 minutes.</h3>";
						break;
					case 1:
						echo "<h3>This account is currently locked out.</h3>";
						break;
					case 2:
						echo "<h3>Username not recognized.</h3>";
						break;
					case 3:
						echo "<h3>Account successfully registered!<br>Please login to continue.</h3>";
						break;
					case 4:
						echo "<h3>Password changed successfully.<br>Please login with your new password to re-authenticate.</h3>";
						break;
					case 5:
						echo "<h3>Updating password has failed.</h3>";
						break;
					case 6:
						echo "<h3>Database Successfully created.</h3>";
						break;
					case 7:
						echo "<h3>Error creating the Database at this time.</h3>";
						break;
					case 8:
						echo "<h3>Login Success!</h3>";
						break;
					case 9:
						echo "<h3>This probably shouldn't have happened...</h3>";
						break;
					case 10:
						$msg = $_SESSION["msg"];
						echo "<h3>'$msg'</h3>";
				}
			}
		?>  
		
		<form action="LoginCheck.php" method="POST">
			<label><b>Username</b></label>
			<input type="text" name="username" placeholder="Enter Username" required><br><br>
	
			<label><b>Password</b></label>
			<input type="password" name="password" placeholder="Enter Password" required><br><br>
	
			<input type="submit" name="submit" value="Submit">
			
			<a href = "/Register.php">
			<input type="button" value="Register">
			</a>
		</form>
		
		<br>
		<form>
			<a href = "/PasswordReset.php">
			<input type="button" value="Password Reset">
			</a>
		</form>
		
		<br>
		<form>
			<a href= "/create_db.php">
			<input type="button" value="Build DataBase">
			</a>
		</form>
	</body>
</html>

<?php
if(isset($_SESSION["errorCode"])){
	unset($_SESSION["errorCode"]);
}
?>