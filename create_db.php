<?php
$servername = "localhost";
$username = "root";
$password = "secret";
$db = "secureapp_db";

//create connection_aborted
$conn = new mysqli($servername, $username, $password);

//Assure connection_aborted
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$create_DB_SQL = "CREATE DATABASE secureapp_db";
if ($conn->query($create_DB_SQL) === TRUE) {
	$conn = new mysqli($servername, $username, $password, $db);

	$create_login_tb = "CREATE TABLE `login_tb` (
						`userID` text NOT NULL,
						`failed_login_count` int(11) NOT NULL,
						`last_failed_login` datetime NOT NULL)";
	
	$create_reset_tb = "CREATE TABLE `reset_tb` (
						`userID` text NOT NULL,
						`token` text NOT NULL,
						`time_issued` datetime NOT NULL)";
						
	$create_user_tb = "CREATE TABLE `user_tb` (
						`user_ID` text NOT NULL,
						`password` text NOT NULL,
						`email` text NOT NULL,
						`dob` text NOT NULL)";
						
	$conn->query($create_login_tb);
	$conn->query($create_reset_tb);
	$conn->query($create_user_tb);
	
	$_SESSION["errorCode"] = 7;
	header("Location:index.php");
	$conn->close();
	exit();
}
else{
	$_SESSION["errorCode"] = 8;
	header("Location:index.php");
	$conn->close();
	exit();
}
?>