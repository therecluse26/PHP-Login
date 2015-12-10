<?php
//Pull '$base_url' and '$signin_url' from this file
include 'globalcon.php';

//DATABASE CONNECTION VARIABLES
$host = "localhost"; // Host name 
$username = "root"; // Mysql username 
$password = "root"; // Mysql password 
$db_name = "login"; // Database name 
$tbl_name = "members"; // Table name
//SET THESE VARIABLES FOR GLOBAL USE 
$site_name = 'Test Site';
$base_url = 'http://' . $_SERVER['SERVER_NAME']; 
//Only set this if you want a moderator to verify users and not the users themselves, otherwise leave blank or comment out
$mod_email = '';
//GLOBAL EMAIL VARIABLES (HTML Rendering Enabled)
$from_email = 'braddmagyar@gmail.com'; //Webmaster email
$from_name = 'Test Email'; //"From name" displayed on email
//HTML Messages shown before URL in emails (the more 
$verifymsg = 'Click this link to verify your new account!'; //Verify email message
$active_email = 'Your new account is now active! Click this link to log in!';//Active email message
//LOGIN FORM RESPONSE MESSAGES/ERRORS
$signupthanks = 'Thank you for signing up! You will receive an email shortly confirming the verification of your account.';
$activemsg = 'Your account has been verified! You may now login at <br><a href="'.$signin_url.'">'.$signin_url.'</a>';

//DO NOT TOUCH
//Unsets $mod_email based on various conditions (left blank, not valid email, etc)
if(trim($mod_email, ' ') == ''){
	unset($mod_email);
}
elseif(!filter_var($mod_email, FILTER_VALIDATE_EMAIL) == true ){
	unset($mod_email);
	echo $invalid_mod; 
};
?>