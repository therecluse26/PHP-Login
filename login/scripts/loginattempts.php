<?php
/******
THIS SCRIPT IS NOT COMPLETE. DO NOT USE!
STILL NEEDS
1) Preventing login (even if password is correct).
2) Updating the last logged in time and checking against current time for the security delay.
3) Anything else I may be forgetting
******/

include_once 'config.php';

//Call this function with $_SERVER['HTTP_CLIENT_IP'] on the checklogin.php page.
function logIP($ipaddress){
	// Connect to server and select databse.
	$db = new PDO('mysql:host='.$host.';dbname=loginAttempts;charset=utf8', $username, $password);


	$stmt = $db->query("SELECT Attempts, LastLogin FROM loginAttempts WHERE IP='".$ipaddress."'");

	// Gets query result
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($result['Attempts'] >= 3){
	//Needs something to actually stop login


		echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Too many failed login attempts, please wait 5 minutes before logging in again</div>";
	}
	else {
	//UPDATE SCRIPT

	}


}
?>
