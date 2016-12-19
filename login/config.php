<?php
//*** Variables in this config file are accessed via "GlobalConf" class ***

//Set this for global site use
$site_name = "Test Site";

//Base website directory on filesystem
$base_dir = "/Applications/MAMP/htdocs/PHP-Login";
$base_url = "http://localhost/PHP-Login";

/***DO NOT CHANGE ***/
$signin_url = $base_url . "/login";
if (!isset($ip_address)) {
    $ip_address = $_SERVER["REMOTE_ADDR"];
}; /**************/

//Global HTML header (without includes)
$htmlhead = "<!DOCTYPE html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content-width=device-width, initial-scale=1, shrink-to-fit=no'>";

//EMAIL SETTINGS
//SEND TEST EMAILS THROUGH FORM TO https://www.mail-tester.com GENERATED ADDRESS FOR SPAM SCORE
$from_email = "example@email.com"; //Webmaster email
$from_name = "Website Name"; //"From name" displayed on email
//SMTP Settings: Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
$mail_server_type = "smtp";
//IF $mail_server_type = "smtp"
$smtp_server = "smtp.email.com";
$smtp_user = "example@email.com";
$smtp_pw = "P@ssw0rd!";
$smtp_port = 587; //465 for ssl, 587 for tls, 25 for other
$smtp_security = "tls";//ssl, tls or ""

//Maximum Login Attempts
$max_attempts = 5;
//Timeout (in seconds) after max attempts are reached
$login_timeout = 300;

//Cookie expiration in seconds (default is 1 month). 0 = expire at end of session
$cookie_expire = 3600 * 24 * 30;

//Password Policy Settings
$password_policy_enforce = true;
$password_min_length = 6;

//Image Settings
//Avatar directory (without base directory)
$avatar_dir = "/user/avatars";

//HTML Messages shown before URL in emails (the more
//Verify email message
$requiresadminapproval = "Thank you for signing up! Before you can login, your account needs to be activated by an administrator.";
$signupthanks = "Thank you for signing up! You will receive an email shortly confirming the verification of your account.";
$active_email = "Your new account is now active! Click this link to log in!";

//Form response messages
$activemsg = "Your account has been verified!";

//Admin verification
$admin_verify = false;

if ($admin_verify == false) {
    //Message if user can self verify
    $verifymsg = "Click this link to verify your new account!";

} else {
    //Message if user cannot self verify and requires admin approval
    $verifymsg = "Thank you for signing up! Your account will be reviewed by a moderator shortly";
}

//JWT secret for "forgot password" resets, cookies and user verification. Can be anything. Longer is better.
$jwt_secret = "php-login";
