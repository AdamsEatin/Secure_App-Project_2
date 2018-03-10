<?php 
session_start();
include 'config.php';


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

//Looking for presence of the userID within user_tb
while($row = $result->fetch_assoc()){
	$row_user = $row['userID'];
	$dec_user = decrypt($row_user);
	if($user == $dec_user){
		$userPresent = TRUE;
		break;
	}
}

//Checking for the presence of the email within user_tb
while($row = $result->fetch_assoc()){
	$row_email = $row['email'];
	$dec_email = decrypt($row_email);
	if($email == $dec_email){
		$emailPresent = TRUE;
		break;
	}
}

//if either the userID or email are present then return the user with an error
if($userPresent || $emailPresent){
	write_file($user, "Attempted to Register with the System", $checkSQL, "UserID or Email was already within the system.");
	$_SESSION["errorCode"] = 1;
	header("Location:Register.php");
	exit();
}
//If the password entered is not valid then return the user saying so
if(!validatePassword($user, $pass)){
	$_SESSION["errorCode"] = 2;
	write_file($user, "Attempted to Register with the System", "validatePassword(" . $user . "," . $pass . ")", "Password was not the correct format.");
	header("Location:Register.php");
	exit();
}

//Encrypt the user' data and then insert into the user_tb
$enc_user = encrypt($user);
$enc_pass = encrypt($pass);
$enc_email = encrypt($email);
$enc_dob = encrypt($dob);

$insert_SQL = "INSERT INTO `user_tb`(`userID`, `password`, `email`, `dob`) VALUES ('$enc_user', '$enc_pass', '$enc_email', '$enc_dob')";
$conn->query($insert_SQL);

//Return to the index page informing the user they have registered.
$_SESSION["errorCode"]=3;
header("Location:index.php");
exit();
?>
			