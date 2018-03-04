<?php 
session_start();
$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "secureapp_db";
$conn = new mysqli($servername, $username, $password, $databasename);

//Retrieving values from form
$userVal = $_POST["username"];
$passVal = $_POST["password"];
$emailVal = $_POST["email"];
$dobVal = $_POST["dob"];

//clean values from special characters
$user = htmlspecialchars($userVal);
$pass = htmlspecialchars($passVal);
$email = htmlspecialchars($emailVal);
$dob = htmlspecialchars($dobVal);

//Assuring that the values have been set
if(!isset($user) || !isset($pass) || !isset($email) || !isset($dob)){
	$errorC = 2;
	$_SESSION["errorCode"] = $errorC;
	header("Location:Register.php");
	exit();
}
else{
	//SQL Query to select all values from table
	$checkSQL = "SELECT * FROM user_tb";
	$result = $conn->query($checkSQL);
	$duplicate = FALSE;
	
	$count = mysqli_num_rows($result);
	if($count != 0){
		//While there are rows present, check for the entered username and email being present
		while($row = $result->fetch_assoc()){
			//If either username or password match, set duplicate to true and break the loop
			if(password_verify($user, $row['userID']) || password_verify($email, $row['email'])){
				$duplicate = TRUE;
				break;
			}
		}
		//If duplicates present, redirect back to the Register page informing the user
		if($duplicate == TRUE){
			$errorC = 1;
			$_SESSION["errorCode"] = $errorC;
			header("Location:Register.php");
			exit();
		}//Else encrypt and store the new details
		else{
			$enc_user = password_hash($user, PASSWORD_DEFAULT);
			$enc_pass = password_hash($pass, PASSWORD_DEFAULT);
			$enc_email = password_hash($email, PASSWORD_DEFAULT);
			$enc_dob = password_hash($dob, PASSWORD_DEFAULT);
			
			$insert_SQL = "INSERT INTO `user_tb`(`userID`, `password`, `email`, `dob`) VALUES ('$enc_user', '$enc_pass', '$enc_email', '$enc_dob')";
			$conn->query($insert_SQL);
			$_SESSION["errorCode"] = 3;
			header("Location:index.php");
			exit();
		}
	}else{
		//Encrypt and enter user details
		$enc_user = password_hash($user, PASSWORD_DEFAULT);
		$enc_pass = password_hash($pass, PASSWORD_DEFAULT);
		$enc_email = password_hash($email, PASSWORD_DEFAULT);
		$enc_dob = password_hash($dob, PASSWORD_DEFAULT);
			
		$insert_SQL = "INSERT INTO `user_tb`(`userID`, `password`, `email`, `dob`) VALUES ('$enc_user', '$enc_pass', '$enc_email', '$enc_dob')";
		$conn->query($insert_SQL);
		$_SESSION["errorCode"] = 3;
		header("Location:index.php");
		exit();
	}
}
?>
			