<?php
require 'scripts/PHPMailer/class.phpmailer.php';
require 'scripts/class.loginscript.php';
include_once 'config.php';

	//Pull username, generate new ID and hash password
	$newid = uniqid (rand(), false);
	$newuser = $_POST['newuser'];
	$newpw = password_hash($_POST['password1'], PASSWORD_DEFAULT);
	$pw1 = $_POST['password1'];
	$pw2 = $_POST['password2'];

	//Enables moderator verification (overrides user self-verification emails)
	if(isset($mod_email)){
		$newemail = $mod_email;	
	}
	else{
		$newemail = $_POST['email'];
	}
		
if($pw1 != $pw2){
	echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Password fields must match</div>';	
}
else {
	
	if (isset($_POST['newuser']) && !empty(str_replace(' ', '', $_POST['newuser'])) && isset($_POST['password1']) && !empty(str_replace(' ', '', $_POST['password1'])) ){

		//Tries inserting into database and add response to variable
		$a = new newUserForm;	
		$response = $a->createUser($newuser, $newid, $newemail, $newpw);	
		//Success
		if($response == 'true'){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'. $signupthanks .'</div>';	

			//Send verification email	
			$m = new mailSender;
			$m->sendMail($newemail, $newuser, $newid, 'Verify');

		}
		//Failure
		else {
		
			mySqlErrors($response);
				
		}

	}

	else {
		//Validation error from empty form variables
		echo 'An error occurred on the form... try again';	

	}
}

?>