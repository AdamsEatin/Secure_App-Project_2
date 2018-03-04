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
		<title>Secure App : Register</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System</h1>
		<h2>Please enter your details below to Register</h2>
		<?php		
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 1:
						echo "<h3>Account successfully registered!<br>Please login to continue.</h3>";
						break;
					case 2:
						echo "<h3>Username or email already present.</h3>";
						break;
					case 3:
						echo "<h3>Values were missing from the form.</h3>";
						break;
					case 4:
						$userS = $_SESSION["userS"];
						$emailS = $_SESSION["emailS"];
						$passS = $_SESSION["passS"];
						$dobS = $_SESSION["dobS"];
						echo "<h3>userID: $userS<br>email: $emailS<br>password: $passS<br>dob: $dobS</h3>";
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
	
			<input type="submit" name="submit" value="Submit" onclick="return validatePassword()">
			<a href = "/Index.php">
			<input type="button" value="Login">
			</a>
		</form>
	</body>
</html>


<?php
    unset($_SESSION["errorCode"]);
?>