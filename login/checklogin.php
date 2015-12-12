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

// Connect to server and select databse.
$login = new loginForm;
$response = $login->checkLogin($tbl_name, $myusername, $mypassword);

	if ($response == 'true'){
		echo "true";
		$_SESSION['username'] = 'myusername';
		$_SESSION['password'] = 'mypassword';
	}
	else {

		echo $response;

	}

ob_end_flush();
?>
