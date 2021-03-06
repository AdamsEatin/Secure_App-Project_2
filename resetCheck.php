<?php 
session_start();
date_default_timezone_set("UTC");
include 'config.php';

$conn = new mysqli($servername, $username, $password, $databasename);

$emailVal = $_POST["email"];
$dobVal = $_POST["dob"];
$tokenVal = $_POST["token"];
$newPasVal = $_POST["newPassword"];
$repPasVal = $_POST["repeatPassword"];

//clean values from special characters
$email = htmlspecialchars($emailVal);
$dob = htmlspecialchars($dobVal);
$token = htmlspecialchars($tokenVal);
$newPassword = htmlspecialchars($newPasVal);
$repeatPassword = htmlspecialchars($repPasVal);

//Pull all rows from user table to compare entered email against
$checkSQL = "SELECT * FROM user_tb";
$result = $conn->query($checkSQL);
$email_present = FALSE;

//checking whether or not the email is present in the main user table
//if it is, set the boolean value to True and break the loop.
while($row = $result->fetch_assoc()){
	$row_email = $row['email'];
	$row_dob = $row['dob'];	
	$dec_email = decrypt($row_email);
	if($email == $dec_email){
		$email_present = TRUE;
		break;
	}
}

//If email_present is set to False, return with an error
if(!$email_present){
	write_file($email, "Attempted to reset password", $checkSQL, "Email was not present in the DB.");
	$_SESSION["errorCode"] = 1;
	header("Location:PasswordReset.php");
	exit();
}

//Getting username for logging and later password validation
$selectSQL = "SELECT * FROM `user_tb` WHERE `email` = '$row_email'";
$selectResult = $conn->query($selectSQL);
$selectRow = $selectResult->fetch_assoc();
$username = $selectRow['userID'];
$decUser = decrypt($username);

//If Date of Birth is incorrect, return with an error
$dec_dob = decrypt($row_dob);
if($dob != $dec_dob){
	write_file($decUser, "Attempted to reset password", "$dob != $dec_dob", "Date of Birth was incorrect");
	$_SESSION["errorCode"] = 2;
	header("Location:PasswordReset.php");
	exit();
}

//Attempting to find the token in reset_tb
$tokenQuerySQL = "SELECT * FROM reset_tb";
$result = $conn->query($tokenQuerySQL);
$token_present = FALSE;

while($row = $result->fetch_assoc()){
	$row_email = $row['email'];
	$row_token = $row['token'];
	$row_exp = $row['expiration'];
	
	if($token == $row_token){
		$token_present = TRUE;
		break;
	}
}

//if the token wasn't found within the table, return with an error
if(!$token_present){
	write_file($decUser, "Attempted to reset password", $tokenQuerySQL, "Reset token was not found");
	$_SESSION["errorCode"] = 5;
	header("Location:PasswordReset.php");
	exit();
}

//get current time for expiration comparison
$curr = time();
$expiration = strtotime($row_exp);

//if the current time is greater than the expiration return the user
if($curr > $expiration){
	write_file($decUser, "Attempted to reset password", "if($curr > $expiration){", "Reset token was expired");
	$_SESSION["errorCode"] = 4;
	header("Location:PasswordReset.php");
	exit();
}

//Taking in the passwords, encrypting them and validating against each other
if(!validatePassword($decUser, $newPasVal)){
	write_file($decUser, "Attempted to reset password", "if(!validatePassword(" . $decUser . ", " . $newPasVal . "){", "Password was not in the correct format");
	$_SESSION["errorCode"] = 3;
	header("Location:PasswordReset.php");
	exit();
}

//Encrypting both password entries and comparing them
$enc_newPas = encrypt($newPasVal);
$enc_repPas = encrypt($repPasVal);
if($enc_newPas !== $enc_repPas){
	write_file($decUser, "Attempted to reset password", "if(" . $enc_newPas . " !== " . $enc_repPas . "){", "Passwords did not match");
	$_SESSION["errorCode"] = 6;
	header("Location:PasswordReset.php");
	exit();
}

//Updating user password in user_tb
$updateSQL = "UPDATE `user_tb` SET `password`='$enc_newPas' WHERE `email`= '$row_email'";
$conn->query($updateSQL);
write_file($decUser, "Attempted to reset password", $updateSQL, "Password was reset");
$_SESSION["errorCode"] = 8;
header("Location:index.php");
exit();

?>
			