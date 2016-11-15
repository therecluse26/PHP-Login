<?php
//*** Variables in this config file are accessed via "confLoad" class ***

//Pull '$base_url', '$base_dir' and '$signin_url' from this file
include 'class/servervars.php';
//Pull database configuration from this file
include 'dbconf.php';

//Set this for global site use
$site_name = 'Test Site';

//Base website directory on filesystem
$base_dir = "/Applications/MAMP/htdocs/PHP-Login";
$base_url = "http://localhost/PHP-Login";

//DO NOT CHANGE
$signin_url = $base_url . '/login';

if(!isset($ip_address)){
    $ip_address = $_SERVER['REMOTE_ADDR'];
};

//Maximum Login Attempts
$max_attempts = 5;
//Timeout (in seconds) after max attempts are reached
$login_timeout = 300;

//JWT secret for "forgot password" resets and user verification. Can be anything.
$jwt_secret = 'php-login';

//ONLY set this if you want a moderator to verify users and not the users themselves, otherwise leave blank or comment out
$admin_email = '';

//EMAIL SETTINGS
//SEND TEST EMAILS THROUGH FORM TO https://www.mail-tester.com GENERATED ADDRESS FOR SPAM SCORE
$from_email = 'email@example.com'; //Webmaster email
$from_name = 'Test Email'; //"From name" displayed on email

//Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
$mail_server_type = 'smtp';
//IF $mail_server_type = 'smtp'
$smtp_server = 'smtp.example.com';
$smtp_user = 'email@example.com';
$smtp_pw = 'p@ssw0rd!';
$smtp_port = 465; //465 for ssl, 587 for tls, 25 for other
$smtp_security = 'tls';//ssl, tls or ''

//HTML Messages shown before URL in emails (the more
//Verify email message
$requiresadminapproval = 'Thank you for signing up! Before you can login, your account needs to be activated by an administrator.';

if ($admin_email != ''){
    // IF YOU WANT MODERATOR VERIFICATION
    $admin_exists = true;
    $verifymsg = 'Thank you for signing up! Your account will be reviewed by a moderator shortly';

}
else {
    //NO MODERATOR VERIFICATION
    $admin_exists = false;
    $verifymsg = 'Click this link to verify your new account!';
}

$active_email = 'Your new account is now active! Click this link to log in!';//Active email message
//LOGIN FORM RESPONSE MESSAGES/ERRORS
$signupthanks = 'Thank you for signing up! You will receive an email shortly confirming the verification of your account.';
$activemsg = 'Your account has been verified! You may now login at <br><a href="'.$signin_url.'">'.$signin_url.'</a>';

//DO NOT TOUCH BELOW THIS LINE
//Unsets $admin_email based on various conditions (left blank, not valid email, etc)
$invalid_mod = '$adminemail is not a valid email address';
if (trim($admin_email, ' ') == '') {
    unset($admin_email);
} elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL) == true) {
    unset($admin_email);
    echo $invalid_mod;
};

//Makes readable version of timeout (in minutes). Do not change.
$timeout_minutes = round(($login_timeout / 60), 1);