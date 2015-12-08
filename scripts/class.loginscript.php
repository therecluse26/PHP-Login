<?php
// Extend this class to re-use db connection
class dbConn {
	
	public $conn;
	public function __construct(){
		
		require 'config.php';
		
		// Connect to server and select database.
		$this->conn = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
		
	 }
}

class loginForm extends dbConn {

	public function createUser($usr, $uid, $email, $pw) {	

		try {
			
			$db = new dbConn;

			$err = '';
			// prepare sql and bind parameters
			$stmt = $db->conn->prepare("INSERT INTO members (id, username, password, email) 
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
	
	public function sendMail($email, $user, $id, $type) {
		
		require 'scripts/PHPMailer/PHPMailerAutoload.php';
		require 'config.php';
		
		$finishedtext = 'Your account has been verified! You may now login at <br><a href="'.$base_url.'">'.$base_url.'</a>';

		// ADD $_SERVER['SERVER_PORT'] TO $verifyurl STRING AFTER $_SERVER['SERVER_NAME'] FOR DEV URLS USING PORTS OTHER THAN 80
		// substr() trims "createuser.php" off of the current URL and replaces with verifyuser.php
		// Can pass 1 (verified) or 0 (unverified/blocked) into url for "v" parameter	
		$verifyurl = substr($base_url . $_SERVER['PHP_SELF'],0, -14) . "verifyuser.php?v=1&uid=" . $id;

		// Create a new PHPMailer instance
		// ADD sendmail_path = "env -i /usr/sbin/sendmail -t -i" to php.ini on UNIX servers
		$mail = new PHPMailer;
		//Sets mail header so HTML renders
		$mail->isHTML(true);
		// Set who the message is to be sent from
		$mail->setFrom("$from_email", "$from_name");
		/****
		* Set who the message is to be sent to
		* CAN BE SET TO addAddress(youremail@website.com, 'Your Name') FOR PRIVATE USER APPROVAL BY MODERATOR
		* SET TO addAddress($email, $user) FOR USER SELF-VERIFICATION 
		*****/
		$mail->addAddress("$email", "$user");

		if ($type == 'Verify') {
			//Set the subject line
			$mail->Subject = $user . ' Account Verification';
			//Set the body of the message
			$mail->Body = 'Click this link to verify your new account!<br>' . '<a href="'.$verifyurl.'">'.$verifyurl.'</a>';
		}
		else {
			//Set the subject line
			$mail->Subject = $site_name . ' Account Created!';
			//Set the body of the message
			$mail->Body = 'Your new account is now active! Click the link below to log in!<br>'
				. '<a href="'.$base_url.'">'.$base_url.'</a>';
		};

			// Sends the message & checks for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}  

	}
	
	
};

?>