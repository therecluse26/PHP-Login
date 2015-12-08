<!DOCTYPE html>
<html>
  <head>
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/main.css" rel="stylesheet" media="screen">
    <meta charset="UTF-8">
    <title>Verify User</title>
  </head>
  <body>
  
  </body>
</html>
<?php
// Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid = $_GET['uid'];
$verify = $_GET['v'];

function verifyUser($uid, $verify) {
	
	include_once 'config.php';
	
	// Connect to server and select database.
	try {
		$verr = '';

		$vconn = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);

		$vconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// prepare sql and bind parameters
		$vstmt = $vconn->prepare("update members set verified = :verify where id = :uid");
		$vstmt->bindParam(':uid', $uid);
		$vstmt->bindParam(':verify', $verify);
		$vstmt->execute();
		
	} catch(PDOException $v) {
		$verr = "Error: " . $v->getMessage();
	}
	
	//Determines returned value ('true' or error code)
	if($verr == ''){
		$vsuccess = 'true'; }
	else{
		$vsuccess = $verr; };
	
	return $vsuccess;
	
};

if (isset($uid) && !empty(str_replace(' ', '', $uid)) && isset($verify) && !empty(str_replace(' ', '', $verify)) ){

	//Updates the verify column on user
	$vresponse = verifyUser($uid, $verify);	
	
	//Success
	if($vresponse == 'true'){
		
		echo "Thank you for signing up! You may now log into your account!<br>";	
						
	}
	//Failure
	else {
		//Echoes error from MySQL
		echo $vresponse;
	}
	
}

else {
	//Validation error from empty form variables
	echo 'An error occurred... click <a href="index.php">here</a> to go back.';	

};

?>