<?php
ob_start();
session_start();
include 'config.php';
require 'scripts/class.loginscript.php';

// Define $myusername and $mypassword
$username = $_POST['myusername'];
$password = $_POST['mypassword'];

// To protect MySQL injection
$username = stripslashes($username);
$password = stripslashes($password);

$response = '';

$loginCtl = new loginForm;
$conf = new globalConf;
$lastAttempt = $loginCtl->checkAttempts($username);
$max_attempts = $conf->max_attempts;

//First Attempt
if($lastAttempt['lastlogin'] == ''){
	$lastlogin = 'never';
	echo $loginCtl->insertAttempt($username);
}
else if ($lastAttempt['attempts'] >= $max_attempts){

	echo 'Exceeded max attempts';

}
else
{
	$lastlogin = $lastAttempt['lastlogin'];
	$attempts = $lastAttempt['attempts'];
	$loginCtl->updateAttempts($username);
};

$response = $loginCtl->checkLogin($username, $password);

if($attempts < $max_attempts && $response != 'success'){
	$loginCtl->updateAttempts($username);
	echo "\$attempts < \$max_attempts && \$response != 'success'";
	//echo $response;
	//echo $update_resp;

}
else if ($response == 'success' && $attempts < $max_attempts){
	echo "successful login";
	//echo $response;
	$loginCtl->resetAttempts($username);
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;

}
else {
	echo "else";
}

ob_end_flush();
?>
