<?php
date_default_timezone_set("UTC");
session_start();
include 'config.php';

$conn = new mysqli($servername, $username, $password, $databasename);

$userVal = $_POST["username"];
$passVal = $_POST["password"];

$user = htmlspecialchars($userVal);
$pass = htmlspecialchars($passVal);

$enc_user = encrypt($user);

//Check table for username
$loginCheckSQL = "SELECT * FROM `user_tb` WHERE `userID` = '$enc_user'";
$result = $conn->query($loginCheckSQL);
$loginRow = $result->fetch_assoc();
$count = mysqli_num_rows($result);

//If no results, return to index
if($count == 0){
	write_file($user, "Attempted Login to the system", $loginCheckSQL, "No user found");
	$_SESSION["errorCode"] = 2;
	header("Location:index.php");
	exit();
}
else{
	$lockoutCheckSQL = "SELECT * FROM `login_tb` WHERE `userID`='$enc_user'";
	$lockoutResult = $conn->query($lockoutCheckSQL);
	$count = mysqli_num_rows($lockoutResult);
	
	
	//If username not present in login_tb make a new entry for it
	if($count == 0){
		$insertSQL = "INSERT INTO `login_tb`(`userID`, `failed_login_count`, `last_failed_login`) VALUES ('$enc_user', 0, now())";
		$conn->query($insertSQL);
		
		$lockoutCheckSQL = "SELECT * FROM `login_tb` WHERE `userID`='$enc_user'";
		$lockoutResult = $conn->query($lockoutCheckSQL);
	}

	//get values associated with row
	$lockoutRow = $lockoutResult->fetch_assoc();
	$row_count = $lockoutRow['failed_login_count'];
	$row_time = strtotime($lockoutRow['last_failed_login']);

	//Calculate time since last failed login
	$now = time();
	$diff = $now - $row_time;
		
	//If failed login count is 5 or greater and the last failed 
	//login attempt was under 5m ago, user is locked out.
	if($row_count >= 5 && $diff < 300){
		$lockedOutSQL = "UPDATE `login_tb` SET `failed_login_count`=`failed_login_count`+1 ,`last_failed_login`=now() WHERE `userID` = '$enc_user'";
		$conn->query($lockedOutSQL);
		write_file($user, "Attempted Login to the system", $lockedOutSQL, "Account is currently locked out.");
		$_SESSION["errorCode"] = 1;
		header("Location:index.php");
		exit();
	}
	else{
		$row_pass = $loginRow['password'];
		$dec_pass = decrypt($row_pass);
		//compare password values
		if($pass == $dec_pass){
			$loginSuccessSQL = "UPDATE `login_tb` SET `failed_login_count`=0 WHERE `userID` = '$enc_user'";
			$conn->query($loginSuccessSQL);
				
			//Send user to welcome page, showing login successful
			write_file($user, "Attempted Login to the system", $loginSuccessSQL, "Successfully logged into the system.");
			header("Location:Welcome.php");
			exit();
		}
		//If password mismatch
		else{
			$loginFailSQL = "UPDATE `login_tb` SET `failed_login_count`=`failed_login_count`+1 ,`last_failed_login`=now() WHERE `userID` = '$enc_user'";
			$conn->query($loginFailSQL);
				
			//Return failed login
			write_file($user, "Attempted Login to the system", $loginFailSQL, "Failed to authenticate with the system.");
			$_SESSION["errorCode"] = 0;
			header("Location:index.php");
			exit();
		}
	}
}
//In the even that an error is not caught
write_file($user, "Attempted Login to the system", "NA", "This probably shouldn't have happened.");
$_SESSION["errorCode"] = 9;
header("Location:index.php");
exit();
?>