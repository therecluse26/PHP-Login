<?php
	
	ob_start();
	session_start();
	include_once 'config.php';

	// Connect to server and select databse.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");


	$value = isset($_POST['myemail']) ? $_POST['myemail'] : '';
	// Define $myusername and $mypassword 
	$myemail = $value;
	$mypassword = $_POST['mypassword']; 
	// To protect MySQL injection
	$myusername = stripslashes($myemail);
	$mypassword = stripslashes($mypassword);
	$myusername = mysql_real_escape_string($myusername);
	$mypassword = mysql_real_escape_string($mypassword);
	$mypassword = sha1($mypassword.$salt);

	$sql="SELECT * FROM $tbl_name WHERE email='$myusername' and password='$mypassword'";
	$result=mysql_query($sql);

	// rowCount() is counting table row
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count == 1){

		// Register $myusername, $mypassword and print "true"
		echo "true";
		$_SESSION['username'] = $row['username'];
		$_SESSION['password'] = 'mypassword';
		
	}
	else {
		//return the error message
		echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Wrong Username or Password</div>";
	}

	ob_end_flush();
?>
