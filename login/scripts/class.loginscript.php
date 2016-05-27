<?php
// Extend this class to re-use db connection
class dbConn {
	public $conn;
	public function __construct(){

		require 'config.php';
		$this->host = $host; // Host name
		$this->username = $username; // Mysql username
		$this->password = $password; // Mysql password
		$this->db_name = $db_name; // Database name
		$this->tbl_prefix = $tbl_prefix; // Prefix for all database tables
		$this->tbl_members = $tbl_members;
		$this->tbl_attempts = $tbl_attempts;
		$this->ip_address = $ip_address;
		$this->login_timeout = $login_timeout;

		// Connect to server and select database.
		$this->conn = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8',$username,$password);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}
};

class selectEmail extends dbConn {

		public function emailPull($id) {


		try {
			$db = new dbConn;
			$tbl_members = $db->tbl_members;

			$stmt = $db->conn->prepare("SELECT email, username FROM ".$tbl_members." WHERE id = :myid");
			$stmt->bindParam(':myid', $id);
			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			}

		catch (PDOException $e) {
			$result = "Error: " . $e->getMessage();
		}

		//Queries database with prepared statement
		return $result;

	}

};

class loginForm extends dbConn {

	public function checkLogin($myusername, $mypassword) {

		//include 'config.php';

		try {

			$db = new dbConn;
			$tbl_members = $db->tbl_members;

			$err = '';
			}

		catch (PDOException $e) {
			$err = "Error: " . $e->getMessage();
		}

		$stmt = $db->conn->prepare("SELECT * FROM ".$tbl_members." WHERE username = :myusername");
		$stmt->bindParam(':myusername', $myusername);
		$stmt->execute();

		// Gets query result
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		// Checks password entered against db password hash
		if(password_verify($mypassword, $result['password']) && $result['verified'] == '1' ){

			// Register $myusername, $mypassword and return "true"
			$success = 'true';

		}

		elseif(password_verify($mypassword, $result['password']) && $result['verified'] == '0' ){

			// Register $myusername, $mypassword and return "true"
			$success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Your account has been created, but you cannot log in until it has been verified</div>";

		}

		else {
			//return the error message
			$success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Wrong Username or Password</div>";


		}

		return $success;

	}

	public function checkAttempts($username){

		try {
			$db = new dbConn;
			$ip_address = $db->ip_address;
			$tbl_attempts = $db->tbl_attempts;
			$err = '';
			$stmt = $db->conn->prepare("SELECT Attempts as attempts, lastlogin FROM ".$tbl_attempts." WHERE IP = :ip and Username = :username");
			$stmt->bindParam(':ip', $ip_address);
			$stmt->bindParam(':username', $username);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result;

			$oldTime = strtotime($result['lastlogin']);

			$newTime = strtotime($datetimeNow);

			$timeDiff = $newTime - $oldTime;

			echo $timeDiff;

		}
		catch (PDOException $e) {
			$err = "Error: " . $e->getMessage();
		}

		//Determines returned value ('true' or error code)
		if ($err == '') {
			$resp = 'true';
		}
		else {
			$resp = $err;
		}

		return $resp;
	}

	public function updateAttempts($username){
		//include 'config.php';
		try {
			$db = new dbConn;
			$ip_address = $db->ip_address;
			$tbl_attempts = $db->tbl_attempts;

			$err = '';

			$datetimeNow = date("Y-m-d H:i:s");

			$att = new loginForm;

			$attcheck = $att->checkAttempts($username);

			$curr_attempts = $attcheck['attempts'];

			$oldTime = strtotime($attcheck['lastlogin']);

			$newTime = strtotime($datetimeNow);

			$timeDiff = $newTime - $oldTime;

			echo $timeDiff;

			if($curr_attempts < 1){
				$stmt = $db->conn->prepare("INSERT INTO ".$tbl_attempts." (ip, attempts, lastlogin, username) values(:ip, 1, :lastlogin, :username)");
				$stmt->bindParam(':ip', $ip_address);
				$stmt->bindParam(':lastlogin', $datetimeNow);
				$stmt->bindParam(':username', $username);
				$stmt->execute();

				$new_attempts = $curr_attempts + 1;


			}
			else{

				if( $timeDiff < $login_timeout ){
						echo "What the frick";
				}
				else {
					$new_attempts = $curr_attempts + 1;
					$stmt2 = $db->conn->prepare("UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username");
					$stmt2->bindParam(':ip', $ip_address);
					$stmt2->bindParam(':attempts', $new_attempts);
					$stmt2->bindParam(':lastlogin', $datetimeNow);
					$stmt2->bindParam(':username', $username);
					$stmt2->execute();
				}

			}

		}
		catch (PDOException $e) {
			$err = "Error: " . $e->getMessage();
		}

		//Determines returned value ('true' or error code)
		if ($err == '') {
			$resp = 'true';
		}
		else {
			$resp = $err;
		};

		return $resp;
	}

	public function resetAttempts($username){
		//include 'config.php';
		try{
			$db = new dbConn;
			$ip_address = $db->ip_address;
			$tbl_attempts = $db->tbl_attempts;
			$stmt = $db->conn->prepare("delete FROM ".$tbl_attempts." WHERE ip = :ip and username = :username");
			$stmt->bindParam(':ip', $ip_address);
			$stmt->bindParam(':username', $username);
			$stmt->execute();
			$resp = 'Reset Attempts';
		}
		catch (PDOException $e) {
			$resp = "Error: " . $e->getMessage();
		}

		return $resp;

	}

	};



class mailSender {

	public function sendMail($email, $user, $id, $type) {
		require 'scripts/PHPMailer/PHPMailerAutoload.php';
		include 'config.php';

		$finishedtext = $active_email;

		// ADD $_SERVER['SERVER_PORT'] TO $verifyurl STRING AFTER $_SERVER['SERVER_NAME'] FOR DEV URLS USING PORTS OTHER THAN 80
		// substr() trims "createuser.php" off of the current URL and replaces with verifyuser.php
		// Can pass 1 (verified) or 0 (unverified/blocked) into url for "v" parameter
		$verifyurl = substr($base_url . $_SERVER['PHP_SELF'],0, -strlen(basename($_SERVER['PHP_SELF']))) . "verifyuser.php?v=1&uid=" . $id;
			//substr($base_url . $_SERVER['PHP_SELF'],0, -14) . "verifyuser.php?v=1&uid=" . $id;

		// Create a new PHPMailer instance
		// ADD sendmail_path = "env -i /usr/sbin/sendmail -t -i" to php.ini on UNIX servers
		$mail = new PHPMailer;
		//Sets mail header so HTML renders
		$mail->isHTML(true);
		//Formatting options
		$mail->CharSet = "text/html; charset=UTF-8;";
		$mail->WordWrap = 80;
		// Set who the message is to be sent from
		$mail->setFrom($from_email, $from_name);
		$mail->AddReplyTo($from_email, $from_name);
		/****
		* Set who the message is to be sent to
		* CAN BE SET TO addAddress(youremail@website.com, 'Your Name') FOR PRIVATE USER APPROVAL BY MODERATOR
		* SET TO addAddress($email, $user) FOR USER SELF-VERIFICATION
		*****/
		$mail->addAddress($email, $user);

		//Sets message body content based on type (verification or confirmation)
		if ($type == 'Verify') {
			//Set the subject line
			$mail->Subject = $user . ' Account Verification';
			//Set the body of the message
			$mail->Body = $verifymsg . '<br><a href="'.$verifyurl.'">'.$verifyurl.'</a>';
			$mail->AltBody  =  $verifymsg . $verifyurl;
		}
		elseif ($type == 'Active') {
			//Set the subject line
			$mail->Subject = $site_name . ' Account Created!';
			//Set the body of the message
			$mail->Body = $active_email . '<br><a href="'.$signin_url.'">'.$signin_url.'</a>';
			$mail->AltBody  =  $active_email . $signin_url;

		};

		//SMTP Settings
		if ($mailServerType == 'smtp'){
			$mail->IsSMTP(); //Enable SMTP
			$mail->SMTPAuth = true; //SMTP Authentication
			$mail->Host = $smtp_server; //SMTP Host
			//Defaults: Non-Encrypted = 25, SSL = 465, TLS = 587
			$mail->SMTPSecure = $smtp_security; // Sets the prefix to the server
			$mail->Port = $smtp_port; //SMTP Port
			//SMTP user auth
			$mail->Username = $smtp_user; //SMTP Username
			$mail->Password = $smtp_pw; //SMTP Password
			//********************
			$mail->SMTPDebug = 0; //Set to 0 to disable debugging (for production)
		}

		try
			{
				$mail->Send();
			}
		catch (phpmailerException $e)
			{
				echo $e->errorMessage();// Error messages from PHPMailer
			}
		catch (Exception $e)
			{
				echo $e->getMessage();// Something else
			}

	}

};

class newUserForm extends dbConn {

	public function createUser($usr, $uid, $email, $pw) {

		//include 'config.php';

		try {

			$db = new dbConn;
			$tbl_members = $db->tbl_members;
			$err = '';
			// prepare sql and bind parameters
			$stmt = $db->conn->prepare("INSERT INTO ".$tbl_members." (id, username, password, email)
			VALUES (:id, :username, :password, :email)");
			$stmt->bindParam(':id', $uid);
			$stmt->bindParam(':username', $usr);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':password', $pw);
			$stmt->execute();
		}
		catch (PDOException $e) {
			$err = "Error: " . $e->getMessage();
		}

		//Determines returned value ('true' or error code)
		if ($err == '') {
			$success = 'true';
		}
		else {
			$success = $err;
		};

		return $success;
	}
};

class verify extends dbConn {
	function verifyUser($uid, $verify) {

		//include 'config.php';

		try {

		$vdb = new dbConn;
		$tbl_members = $db->tbl_members;

		$verr = '';

		// prepare sql and bind parameters
		$vstmt = $vdb->conn->prepare("UPDATE ".$tbl_members." SET verified = :verify WHERE id = :uid");
		$vstmt->bindParam(':uid', $uid);
		$vstmt->bindParam(':verify', $verify);
		$vstmt->execute();

	} catch(PDOException $v) {
		$verr = "Error: " . $v->getMessage();
	}

	// Connect to server and select database.


	//Determines returned value ('true' or error code)
	if($verr == ''){
		$vresponse = 'true'; }
	else{
		$vresponse = $verr; };

	return $vresponse;
	}

};

function mySqlErrors ($response) {

	//Returns custom error messages instead of MySQL errors
	switch(substr($response, 0, 22)){
		case 'Error: SQLSTATE[23000]':
			echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Username or email already exists</div>";
			break;
		default:
			echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>An error occurred... try again</div>";
	}
}

?>
