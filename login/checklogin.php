<?php
ob_start();
session_start();
include_once 'config.php';
require 'scripts/class.loginscript.php';

// Define $myusername and $mypassword
$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];

// To protect MySQL injection
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);

$response = '';

$login = new loginForm;
$attempts = $login->checkAttempts($ip_address, $myusername);

if($attempts['lastlogin'] == ''){
		$lastlogin = 'never';
}
else
{
	$lastlogin = $attempts['lastlogin'];
};

echo $lastlogin;
//print_r($attempts);


if($attempts < $max_attempts){
	$update_resp = $login->updateAttempts($ip_address, $myusername);
	$response = $login->checkLogin($tbl_name, $myusername, $mypassword);
	echo $response . 'doot';
	echo $update_resp;

}

	else if ($response == 'true' && $attempts < $max_attempts){

		echo $response;
		$_SESSION['username'] = 'myusername';
		$_SESSION['password'] = 'mypassword';
		$login->resetAttempts($ip_address, $myusername);

	}

	else {

		echo $response;

	}

ob_end_flush();
?>
