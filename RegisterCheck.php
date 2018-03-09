<?php 
session_start();
include 'config.php';
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

//SQL Query to pull all rows from the table
$checkSQL = "SELECT * FROM user_tb";
$result = $conn->query($checkSQL);

$userPresent = FALSE;
$emailPresent = FALSE;

while($row = $result->fetch_assoc()){
	$row_user = $row['userID'];
	$dec_user = decrypt($row_user);
	if($user == $dec_user){
		$userPresent = TRUE;
		break;
	}
}

while($row = $result->fetch_assoc()){
	$row_email = $row['email'];
	$dec_email = decrypt($row_email);
	if($email == $dec_email){
		$emailPresent = TRUE;
		break;
	}
}

if($userPresent || $emailPresent ){
	$_SESSION["errorCode"] = 1;
	header("Location:Register.php");
	exit();
}

if(!validatePassword($pass)){
	$_SESSION["errorCode"] = 2;
	header("Location:Register.php");
	exit();
}

$enc_user = encrypt($user);
$enc_pass = encrypt($pass);
$enc_email = encrypt($email);
$enc_dob = encrypt($dob);

$insert_SQL = "INSERT INTO `user_tb`(`userID`, `password`, `email`, `dob`) VALUES ('$enc_user', '$enc_pass', '$enc_email', '$enc_dob')";
$conn->query($insert_SQL);

$_SESSION["errorCode"]=3;
header("Location:index.php");
exit();
?>
			