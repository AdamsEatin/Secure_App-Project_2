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

//Checking that values have been set
if(!isset($user) || !isset($pass) || !isset($email) || !isset($dob)){
	$errorC = 2;
	$_SESSION["errorCode"] = $errorC;
	header("Location:Register.php");
	exit();
}
else{
	//SQL Query to pull all rows from the table
	$checkSQL = "SELECT * FROM user_tb";
	$result = $conn->query($checkSQL);
	$count = mysqli_num_rows($result);
	
	//If there are any rows in the table we have to check for duplicates
	if($count != 0){
		//boolean values for presence of duplicate username or email
		$dup_user = FALSE;
		$dup_email = FALSE;
		
		//If either the username or email are already present, update the associated boolean values
		while($row = $result->fetch_assoc()){
			if($user == $row['userID']){
				$dup_user = TRUE;
			}
			if(password_verify($email, $row['email'])){
				$dup_email == TRUE;
			}
		}
		
		//If either of the booleans are set to True, return the user with an error
		if($dup_user || $dup_email){
			$errorC = 1;
			$_SESSION["errorCode"] = $errorC;
			header("Location:Register.php");
			exit();
		}
		
		//otherwise encrypt and enter the users information
		$enc_pass = password_hash($pass, PASSWORD_DEFAULT);
		$enc_email = password_hash($email, PASSWORD_DEFAULT);
		$enc_dob = password_hash($dob, PASSWORD_DEFAULT);
			
		$insert_SQL = "INSERT INTO `user_tb`(`userID`, `password`, `email`, `dob`) VALUES ('$user', '$enc_pass', '$enc_email', '$enc_dob')";
		$conn->query($insert_SQL);
		$_SESSION["errorCode"] = 3;
		header("Location:index.php");
		exit();
	}
	//If there are no current entries in the table we can simply insert the user' information
	else{
		$enc_pass = password_hash($pass, PASSWORD_DEFAULT);
		$enc_email = password_hash($email, PASSWORD_DEFAULT);
		$enc_dob = password_hash($dob, PASSWORD_DEFAULT);
			
		$insert_SQL = "INSERT INTO `user_tb`(`userID`, `password`, `email`, `dob`) VALUES ('$user', '$enc_pass', '$enc_email', '$enc_dob')";
		$conn->query($insert_SQL);
		$_SESSION["errorCode"] = 3;
		header("Location:index.php");
		exit();
	}
}
?>
			