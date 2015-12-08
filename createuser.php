<?php
require 'scripts/PHPMailer/class.phpmailer.php';
require 'scripts/class.loginscript.php';

	//Pull username, generate new ID and hash password
	$newid = uniqid (rand(), false);
	$newuser = $_POST['newuser'];
	$newemail = $_POST['email'];
	$newpw = password_hash($_POST['password1'], PASSWORD_DEFAULT);

	$a = new loginForm;


if (isset($_POST['newuser']) && !empty(str_replace(' ', '', $_POST['newuser'])) && isset($_POST['password1']) && !empty(str_replace(' ', '', $_POST['password1'])) ){

	//Tries inserting into database and add response to variable
	$response = $a->createUser($newuser, $newid, $newemail, $newpw);	
	
	//Success
	if($response == 'true'){
		
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Thank you for signing up! You will receive an email shortly confirming the verification of your account.</div>";	
		
		//Send mail
		
		//include 'mailsend.php';
		
		$a->sendMail($newemail, $newuser, $newid, 'Verify');

	}
	//Failure
	else {
		//Echoes error from MySQL
		echo $response;
	}
	
}

else {
	//Validation error from empty form variables
	echo 'An error occurred on the form... try again.';	

};

?>