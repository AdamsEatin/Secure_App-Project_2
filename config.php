<?php
$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "secureapp_db";
$key = "super_secret_key_12345";
$log_key = "not_so_super_secret_key_54321";

date_default_timezone_set("UTC");

function validatePassword($username, $password){
	$lowUser = strtolower($username);
	$lowPass = strtolower($password);
	
	//check for immediately containing username
	if(strpos($lowPass, $lowUser) !== FALSE){
		return FALSE;
		exit();
	}
	
	//check for presence of various delimiters
	//	-fullstop, comma, dash, underscore, space, pound sign, tabs
	$delims = array('.', ',', '-', '_', ' ', '£', '\t');
	foreach($delims as $val){	
		if(strpos($lowUser, $val) !== FALSE){
			$subStrings = explode($val, $lowUser);
			foreach($subStrings as &$x){
				if(strpos($x, $lowPass) !== FALSE){
					return FALSE;
					exit();
				}
			}
		}
	}
	
	$count = 0;
	//Checking for the password length
	if(preg_match("/^\S{8,}$/", $password)){
		$count++;
	}
	//Checking for Upper Characters
	if(preg_match("/[A-Z]/", $password)){
		$count++;
	}
	//Checking for lower characters
	if(preg_match("/[a-z]/", $password)){
		$count++;
	}
	//Checking for digits
	if(preg_match("/[0-9]/", $password)){
		$count++;
	}
	//Checking for symbols
	if(preg_match("/[-!$%^&*()_+|~=`{}\[\]:\";'<>?,.\/]/", $password)){
		$count++;
	}
	//Checking for non-english unicode characters
	if(preg_match("/[\x{0080}-\x{FFFF}]/", $password)){
		$count++;
	}
	//If less than three conditions were met, return failed.
	if($count < 3){
		return FALSE;
		exit();
	}
	
	return TRUE;
	exit();
}

function encrypt($textToEncrypt){
	global $key;
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $textToEncrypt, MCRYPT_MODE_CBC, md5(md5($key))));
}

function decrypt($textToDecrypt){
	global $key;
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($textToDecrypt), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
}

function write_file($who, $where, $what, $result){
	$date = date('Y-m-d H:i:s');
	$log = "$who" . ' - ' . $date . ' - ' . $where . ' - ' . $what . ' - ' . $result;
	/*
	$logfile = fopen('log.txt', 'a+');
	$contents = fread($logfile, filesize('log.txt'));
	$enc_log = encrypt($log);
	$new_log = $contents . $enc_log . "\r\n";
	
	//$enc_log = encrypt($new_dec_log);
	*/
	$logfile = fopen('log.txt', 'a+');
	$enc_log = encrypt($log);
	
	fwrite($logfile, $enc_log . "\r\n");
	fclose($logfile);
}
?>