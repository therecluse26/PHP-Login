<?php
ob_start();
session_start();
include 'config.php';
require 'scripts/class.loginscript.php';

// Define $myusername and $mypassword
$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];

// To protect MySQL injection
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);

$response = '';

$login = new loginForm;
$attemptinfo = $login->checkAttempts($myusername);


if($attemptinfo['lastlogin'] == ''){
	$lastlogin = 'never';
	$attempts = 0;
	$response = $login->checkLogin($myusername, $mypassword);
}
else
{
	$lastlogin = $attemptinfo['lastlogin'];
	$attempts = $attemptinfo['attempts'];
	$response = $login->checkLogin($myusername, $mypassword);
};


if($attempts < $max_attempts && $response != 'true'){
	$update_resp = $login->updateAttempts($myusername);
	echo $response;
	echo $update_resp;

}
else if ($response == 'true' && $attempts < $max_attempts){
	echo $response;
	$_SESSION['username'] = 'myusername';
	$_SESSION['password'] = 'mypassword';
	$login->resetAttempts($myusername);

}
else {

	echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Maximum number of login attempts exceeded... please wait ".$timeout_minutes." minutes before logging in again</div>";

}

ob_end_flush();
?>
