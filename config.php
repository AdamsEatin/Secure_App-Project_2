<?php
$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "secureapp_db";
$key = "super_secret_key_12345";


function validatePassword($password){
	$regex = "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/";
	if(preg_match($regex, $password)){
		return TRUE;
		exit();
	}
	return FALSE;
}

function encrypt($textToEncrypt){
	global $key;
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $textToEncrypt, MCRYPT_MODE_CBC, md5(md5($key))));
}

function decrypt($textToDecrypt){
	global $key;
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($textToDecrypt), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
}
?>