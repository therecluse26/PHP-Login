<?php
//*** Variables in this config file are accessed via "GlobalConf" class ***

//Set this for global site use
$site_name = "Test Site";

//Base website directory on filesystem
$base_dir = "/Applications/MAMP/htdocs/PHP-Login";
$base_url = "http://localhost/PHP-Login";

/***DO NOT CHANGE ***/
$signin_url = $base_url . "/login";
if(!isset($ip_address)){
    $ip_address = $_SERVER["REMOTE_ADDR"];
}; /**************/

//Global HTML header (without includes)
$htmlhead = "<!DOCTYPE html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content-width=device-width, initial-scale=1, shrink-to-fit=no'>";

//EMAIL SETTINGS
//SEND TEST EMAILS THROUGH FORM TO https://www.mail-tester.com GENERATED ADDRESS FOR SPAM SCORE
$from_email = "example@email.com"; //Webmaster email
$from_name = "Website"; //"From name" displayed on email
//SMTP Settings: Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
$mail_server_type = "smtp";
//IF $mail_server_type = "smtp"
$smtp_server = "smtp.gmail.com";
$smtp_user = "example@email.com";
$smtp_pw = "P@ssw0rd!";
$smtp_port = 587; //465 for ssl, 587 for tls, 25 for other
$smtp_security = "tls";//ssl, tls or ""

//Maximum Login Attempts
$max_attempts = 5;
//Timeout (in seconds) after max attempts are reached
$login_timeout = 300;

//Password Policy Settings
$password_policy_enforce = true;
$password_min_length = 6;

//Image Settings
$avatar_dir = "/user/avatars";

//HTML Messages shown before URL in emails (the more
//Verify email message
$requiresadminapproval = "Thank you for signing up! Before you can login, your account needs to be activated by an administrator.";
$signupthanks = "Thank you for signing up! You will receive an email shortly confirming the verification of your account.";
$active_email = "Your new account is now active! Click this link to log in!";

//Form response messages
$activemsg = "Your account has been verified!";

//ONLY set this if you want a moderator to verify users and not the users themselves, otherwise leave blank or comment out
$admin_email = "";

//DO NOT TOUCH BELOW THIS LINE
//Unsets $admin_email based on various conditions (left blank, not valid email, etc)
$invalid_mod = "{$admin_email} is not a valid email address";
if (trim($admin_email, " ") == "") {
    //NO MODERATOR VERIFICATION
    unset($admin_email);
    $admin_verify = false;
    $verifymsg = "Click this link to verify your new account!";
} elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL) == true) {
    // INVALID MODERATOR EMAIL
    unset($admin_email);
    echo $invalid_mod;
} else {
    // IF YOU WANT MODERATOR VERIFICATION
    $admin_verify = true;
    $verifymsg = "Thank you for signing up! Your account will be reviewed by a moderator shortly";
}

//JWT secret for "forgot password" resets and user verification. Can be anything.
$jwt_secret = "php-login";

//Makes readable version of timeout (in minutes). Do not change.
$timeout_minutes = round(($login_timeout / 60), 1);
