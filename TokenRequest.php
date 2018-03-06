<?php 
session_start();
date_default_timezone_set("UTC");

$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "secureapp_db";
$conn = new mysqli($servername, $username, $password, $databasename);

$emailVal = $_POST["email"];

//clean values from special characters
$email = htmlspecialchars($emailVal);


//Pull all rows from user table to compare entered email against
$checkSQL = "SELECT * FROM user_tb";
$result = $conn->query($checkSQL);
$email_present = FALSE;

//checking whether or not the email is present in the main user table
//if it is, set the boolean value to True and break the loop.
while($row = $result->fetch_assoc()){
	$row_email = $row['email'];
	if(password_verify($email, $row_email)){
		$email_present = TRUE;
		break;
	}
}

if($email_present){
	//generating unique, random token and setting session variable
	$token = bin2hex(random_bytes(24));
	$_SESSION["reset_tok"] = $token;
	
	//Setting the token' expiration in 5 minutes
	$time = time() + (60*5);
	$expiration = date("Y-m-d\ H:i:s", $time);
	
	$selectSQL = "SELECT * FROM `reset_tb` WHERE `email`='$row_email'";
	$reset_result = $conn->query($selectSQL);
	$count = mysqli_num_rows($reset_result);
	
	//if no results found, go ahead and enter in a new value for this email' token
	if($count == 0){
		//entering values into reset_tb
		$insertSQL = "INSERT INTO `reset_tb`(`email`, `token`, `expiration`) VALUES ('$row_email', '$token', '$expiration')";
		$conn->query($insertSQL);
	
		//redirecting back to the RequestToken Page
		$_SESSION["errorCode"] = 7;
		header("Location:PasswordReset.php");
		exit();
	}
	else{
		//Update the existing entry with the newly generated token, update it's expiration datetime
		$updateSQL = "UPDATE `reset_tb` SET `token`='$token',`expiration`='$expiration' WHERE `email`='$row_email'";
		$conn->query($updateSQL);
		
		//redirecting back to the RequestToken Page
		$_SESSION["errorCode"] = 7;
		header("Location:PasswordReset.php");
		exit();
	
	}
}
else{
	$_SESSION["errorCode"] = 1;
	header("Location:RequestToken.php");
	exit();
}
?>
			