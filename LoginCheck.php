<?php
date_default_timezone_set("UTC");
session_start();

$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "secureapp_db";
$conn = new mysqli($servername, $username, $password, $databasename);

$userVal = $_POST["username"];
$passVal = $_POST["password"];

$user = htmlspecialchars($userVal);
$pass = htmlspecialchars($passVal);

//Check table for username
$loginCheckSQL = "SELECT * FROM user_tb";
$loginResult = $conn->query($loginCheckSQL);
$loginFound = FALSE;

while($loginRow = $loginResult->fetch_assoc()){
	if(password_verify($user, $loginRow['userID'])){
		$userN = $loginRow['userID'];
		$passW = $loginRow['password'];
 		$loginFound = TRUE;
		break;
	}
}
//Username not found in DB
if($loginFound == FALSE){
	$_SESSION["errorCode"] = 2;
	header("Location:index.php");
	exit();
}
//If username found in DB
else{
	$lockoutCheckSQL = "SELECT * FROM `login_tb` WHERE `userID`='$userN'";
	$lockoutResult = $conn->query($lockoutCheckSQL);
	$count = mysqli_num_rows($lockoutResult);
	
	//If username not present in login_tb make a new entry for it
	if($count == 0){
		$insertSQL = "INSERT INTO `login_tb`(`userID`, `failed_login_count`, `last_failed_login`) VALUES ('$userN', 0, now())";
		$conn->query($insertSQL);
		
		$lockoutCheckSQL = "SELECT * FROM `login_tb` WHERE `userID`='$userN'";
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
		$lockedOutSQL = "UPDATE `login_tb` SET `failed_login_count`=`failed_login_count`+1 ,`last_failed_login`=now() WHERE `userID` = '$userN'";
		$conn->query($lockedOutSQL);	
		$_SESSION["errorCode"] = 1;
		header("Location:index.php");
		exit();
	}
	else{
		//compare password values
		if(password_verify($pass, $passW)){
			$loginSuccessSQL = "UPDATE `login_tb` SET `failed_login_count`=0 WHERE `userID` = '$userN'";
			$conn->query($loginSuccessSQL);
				
			//Temp return to index showing login success
			$_SESSION["errorCode"] = 8;
			header("Location:index.php");
			exit();
			//TODO : proceed to welcome page
		}
		//If password mismatch
		else{
			$loginFailSQL = "UPDATE `login_tb` SET `failed_login_count`=`failed_login_count`+1 ,`last_failed_login`=now() WHERE `userID` = '$userN'";
			$conn->query($loginFailSQL);
				
			//Return failed login
			$_SESSION["errorCode"] = 0;
			header("Location:index.php");
			exit();
		}
	}
}
//In the even that an error is not caught
$_SESSION["errorCode"] = 9;
header("Location:index.php");
exit();

?>

