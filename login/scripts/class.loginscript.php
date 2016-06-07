<?php
class GlobalConf
{
    public $conf;
    public static $attempts;
    public function __construct()
    {
        require 'config.php';
        $this->ip_address = $ip_address;
        $this->login_timeout = $login_timeout;
        $this->timeout_minutes = $timeout_minutes;
        $this->base_url = $base_url;
        $this->signin_url = $signin_url;
        $this->max_attempts = $max_attempts;
    }

    public function addAttempt()
    {
        $attempts++;
    }
    public function resetAttempts()
    {
        $attempts = 0;
    }
}

class RespObj
{
    public $username;
    public $response;
    public function __construct($username, $response)
	{
        $this->username = $username;
        $this->response = $response;
    }
}

// Extend this class to re-use db connection
class DbConn
{
    public $conn;
    public function __construct()
	{
        require 'dbconn.php';
        $this->host = $host; // Host name
        $this->username = $username; // Mysql username
        $this->password = $password; // Mysql password
        $this->db_name = $db_name; // Database name
        $this->tbl_prefix = $tbl_prefix; // Prefix for all database tables
        $this->tbl_members = $tbl_members;
        $this->tbl_attempts = $tbl_attempts;

        // Connect to server and select database.
        $this->conn = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
};
function checkAttempts($username)
{
	try {
		$db = new DbConn;
		$conf = new GlobalConf;
		$tbl_attempts = $db->tbl_attempts;
		$ip_address = $conf->ip_address;
		$err = '';
		$sql = "SELECT Attempts as attempts, lastlogin FROM ".$tbl_attempts." WHERE IP = :ip and Username = :username";
		$stmt = $db->conn->prepare($sql);
		$stmt->bindParam(':ip', $ip_address);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;

		$oldTime = strtotime($result['lastlogin']);
		$newTime = strtotime($datetimeNow);

		$timeDiff = $newTime - $oldTime;

	}
	catch (PDOException $e) {
		$err = "Error: " . $e->getMessage();
	}

	//Determines returned value ('true' or error code)
	$resp = ($err == '') ? 'true' : $err;

	return $resp;
};

class SelectEmail extends DbConn {

    public function emailPull($id)
	{
        try
		{
            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            $stmt = $db->conn->prepare("SELECT email, username FROM ".$tbl_members." WHERE id = :myid");
            $stmt->bindParam(':myid', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            }

        catch (PDOException $e)
		{
            $result = "Error: " . $e->getMessage();
        }

        //Queries database with prepared statement
        return $result;

    }

};

class LoginForm extends DbConn {

    public function checkLogin($myusername, $mypassword) {

        $conf = new GlobalConf;
        $ip_address = $conf->ip_address;
        $login_timeout = $conf->login_timeout;
        $max_attempts = $conf->max_attempts;
        $timeout_minutes = $conf->timeout_minutes;

        $attcheck = checkAttempts($myusername);
        $curr_attempts = $attcheck['attempts'];

        $datetimeNow = date("Y-m-d H:i:s");
        $oldTime = strtotime($attcheck['lastlogin']);
        $newTime = strtotime($datetimeNow);

        $timeDiff = $newTime - $oldTime;

        try {

            $db = new DbConn;
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

        //Too many failed attempts
        if($curr_attempts >= $max_attempts && $timeDiff < $login_timeout){
            $success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Maximum number of login attempts exceeded... please wait ".$timeout_minutes." minutes before logging in again</div>";
            }
        //If max attempts not exceeded, continue
        else {
            // Checks password entered against db password hash
            if(password_verify($mypassword, $result['password']) && $result['verified'] == '1' ){
                //Success! Register $myusername, $mypassword and return "true"
                $success = 'true';
            }

            elseif(password_verify($mypassword, $result['password']) && $result['verified'] == '0' ){
                //Account not yet verified
                $success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Your account has been created, but you cannot log in until it has been verified</div>";

            }
            else {
                //Wrong username or password
                $success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Wrong Username or Password</div>";
            }
        }
        return $success;

    }

    public function insertAttempt($username){
        try {
            $db = new DbConn;
            $conf = new GlobalConf;
            $tbl_attempts = $db->tbl_attempts;
            $ip_address = $conf->ip_address;
            $login_timeout = $conf->login_timeout;
            $max_attempts = $conf->max_attempts;
            $datetimeNow = date("Y-m-d H:i:s");

            $attcheck = checkAttempts($myusername);
            $curr_attempts = $attcheck['attempts'];

            $stmt = $db->conn->prepare("INSERT INTO ".$tbl_attempts." (ip, attempts, lastlogin, username) values(:ip, 1, :lastlogin, :username)");
            $stmt->bindParam(':ip', $ip_address);
            $stmt->bindParam(':lastlogin', $datetimeNow);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $curr_attempts++;
            $err = '';

            }
        catch (PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }

        //Determines returned value ('true' or error code)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;

    }

    public function updateAttempts($username){
        //include 'config.php';
        try {
            $db = new DbConn;
            $conf = new GlobalConf;
            $tbl_attempts = $db->tbl_attempts;
            $ip_address = $conf->ip_address;
            $login_timeout = $conf->login_timeout;
            $max_attempts = $conf->max_attempts;
            $timeout_minutes = $conf->timeout_minutes;

            $err = '';

            $datetimeNow = date("Y-m-d H:i:s");

            $att = new LoginForm;

            $attcheck = checkAttempts($username);

            $curr_attempts = $attcheck['attempts'];

            $oldTime = strtotime($attcheck['lastlogin']);
            $newTime = strtotime($datetimeNow);

            $timeDiff = $newTime - $oldTime;

            $sql = '';

                if($curr_attempts >= $max_attempts && $timeDiff < $login_timeout){

                    if ($timeDiff >= $login_timeout) {

                        $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                        $curr_attempts = 0;

                    }
                }

                else {

                    if( $timeDiff < $login_timeout ){
                        $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                        $curr_attempts++;
                    }
                    else if ($timeDiff >= $login_timeout ) {

                        $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                        $curr_attempts = 0;

                    }
                        $stmt2 = $db->conn->prepare($sql);
                        $stmt2->bindParam(':attempts', $curr_attempts);
                        $stmt2->bindParam(':ip', $ip_address);
                        $stmt2->bindParam(':lastlogin', $datetimeNow);
                        $stmt2->bindParam(':username', $username);
                        $stmt2->execute();
                }
        }
        catch (PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }

        //Determines returned value ('true' or error code)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;
    }

    public function resetAttempts($username){
        //include 'config.php';
        try{
            $db = new DbConn;
            $conf = new GlobalConf;
            $tbl_attempts = $db->tbl_attempts;
            $stmt = $db->conn->prepare("delete FROM ".$tbl_attempts." WHERE username = :username");
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

class MailSender {

    public function sendMail($email, $user, $id, $type) {
        require 'scripts/PHPMailer/PHPMailerAutoload.php';
        include 'config.php';

        $finishedtext = $active_email;

        // ADD $_SERVER['SERVER_PORT'] TO $verifyurl STRING AFTER $_SERVER['SERVER_NAME'] FOR DEV URLS USING PORTS OTHER THAN 80
        // substr() trims "createuser.php" off of the current URL and replaces with verifyuser.php
        // Can pass 1 (verified) or 0 (unverified/blocked) into url for "v" parameter
        $verifyurl = substr($base_url . $_SERVER['PHP_SELF'],0, -strlen(basename($_SERVER['PHP_SELF']))) . "verifyuser.php?v=1&uid=" . $id;

        // Create a new PHPMailer object
        // ADD sendmail_path = "env -i /usr/sbin/sendmail -t -i" to php.ini on UNIX servers
        $mail = new PHPMailer;
        $mail->isHTML(true);
        $mail->CharSet = "text/html; charset=UTF-8;";
        $mail->WordWrap = 80;
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

class newUserForm extends DbConn {

    public function createUser($usr, $uid, $email, $pw) {

        try {

            $db = new DbConn;
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

class verify extends DbConn {
    function verifyUser($uid, $verify) {

        try {
        $vdb = new DbConn;
        $tbl_members = $vdb->tbl_members;
        $verr = '';

        // prepare sql and bind parameters
        $vstmt = $vdb->conn->prepare('UPDATE '.$tbl_members.' SET verified = :verify WHERE id = :uid');
        $vstmt->bindParam(':uid', $uid);
        $vstmt->bindParam(':verify', $verify);
        $vstmt->execute();

    } catch(PDOException $v) {
        $verr = 'Error: ' . $v->getMessage();
    }

    //Determines returned value ('true' or error code)
    $resp = ($verr == '') ? 'true' : $verr;

    return $resp;
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
