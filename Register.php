<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Secure App System 2.0 : Register</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System 2.0 : Registration</h1>
		<h2>Please enter your details below to Register</h2>
		<?php		
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 1:
						echo "<h3>Username or email already present.</h3>";
						break;
					case 2:
						echo "<h3>Password did not match the required format</h3>";
						break;
				}
			}
		?>  
		<form name= "regForm" action="RegisterCheck.php" method="POST">
			<label><b>Username</b></label>
			<input type="text" name="username" placeholder="Enter Username" required><br><br>
	
			<label><b>Password</b></label>
			<input type="password" name="password" placeholder="Enter Password" required><br><br>
			
			<label><b>Email</b></label>
			<input type="email" name="email" placeholder="Enter Email" required><br><br>
	
			<label><b>Date Of Birth</b></label>
			<input type="date" name="dob" required><br><br>
	
			<input type="submit" name="submit" value="Register">
			<a href = "/Index.php">
			<input type="button" value="Home">
			</a>
		</form>
	</body>
</html>


<?php
    unset($_SESSION["errorCode"]);
?>