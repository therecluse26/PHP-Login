<?php
//CREATES dbconf.php FILE
try {
    $status = "Generating dbconf.php file";
    $dbconf_file = fopen('../login/testdbconf.php', 'w') or die('Unable to open file!');
    $dbconf_text = '<?php
    //DATABASE CONNECTION VARIABLES
    $host = "'.$dbhost.'"; // Host name
    $username = "'.$dbuser.'"; // Mysql username
    $password = "'.$dbpw.'"; // Mysql password
    $db_name = "'.$dbname.'"; // Database name
    $superadmin = "'.$superadmin.'"; // Superadmin name

    //DO NOT CHANGE BELOW THIS LINE UNLESS YOU CHANGE THE NAMES OF THE MEMBERS AND LOGINATTEMPTS TABLES

    $tbl_prefix = "'.$tblprefix.'"; //***PLANNED FEATURE, LEAVE VALUE BLANK FOR NOW*** Prefix for all database tables
    $tbl_members = $tbl_prefix."members";
    $tbl_memberinfo = $tbl_prefix."memberInfo";
    $tbl_admins = $tbl_prefix."admins";
    $tbl_attempts = $tbl_prefix."loginAttempts";
    $tbl_deleted = $tbl_prefix."deletedMembers";
    ';

    fwrite($dbconf_file, $dbconf_text);
    fclose($dbconf_file);

    $i++;

} catch (Exception $f) {
    echo "Failed to write dbconf.php file" , $f->getMessage(), "\n";
}
//CREATES config.php FILE
try {
    $status = "Generating config.php file";
    $config_file = fopen('../login/testconfig.php', 'w') or die('Unable to open file!');
    $config_text = '<?php
//Pull "$base_url", "$base_dir" and "$signin_url" from this file
include "class/servervars.php";
//Pull database configuration from this file
include "dbconf.php";

//Set this for global site use
$site_name = "'.$site_name.'";

//Maximum Login Attempts
$max_attempts = 5;
//Timeout (in seconds) after max attempts are reached
$login_timeout = 300;

//JWT secret for "forgot password" resets and user verification. Can be anything.
$jwt_secret = "php-login";

//ONLY set this if you want a moderator to verify users and not the users themselves, otherwise leave blank or comment out
$admin_verification = 0;
$admin_email = "'.$saemail.'";

//EMAIL SETTINGS
//SEND TEST EMAILS THROUGH FORM TO https://www.mail-tester.com GENERATED ADDRESS FOR SPAM SCORE
$from_email = "'.$smtp_fromEmail.'"; //Webmaster email
$from_name = "'.$smtp_fromName.'"; //"From name" displayed on email

//Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
$mail_server_type = "smtp";
//IF $mail_server_type = "smtp"
$smtp_server = "'.$smtp_server.'";
$smtp_user = "'.$smtp_user.'";
$smtp_pw = "'.$smtp_pw.'";
$smtp_port = "'.$smtp_port.'"; //465 for ssl, 587 for tls, 25 for other
$smtp_security = "'.$smtp_security.'"; //ssl, tls or ""

//HTML Messages shown before URL in emails (the more
//Verify email message
$requiresadminapproval = "Thank you for signing up! Before you can login, your account needs to be activated by an administrator.";

if ($admin_verification == 1 && isset($admin_email)){
    // IF YOU WANT MODERATOR VERIFICATION
    $admin_exists = true;
    $verifymsg = "Thank you for signing up! Your account will be reviewed by a moderator shortly";

}
else {
    //NO MODERATOR VERIFICATION
    $admin_exists = false;
    $verifymsg = "Click this link to verify your new account!";
}

$active_email = "Your new account is now active! Click this link to log in!";//Active email message
//LOGIN FORM RESPONSE MESSAGES/ERRORS
$signupthanks = "Thank you for signing up! You will receive an email shortly confirming the verification of your account.";
$activemsg = "Your account has been verified! You may now login at <br><a href=".$signin_url.">".$signin_url."</a>";

//DO NOT TOUCH BELOW THIS LINE
//Unsets $admin_email based on various conditions (left blank, not valid email, etc)
$invalid_mod = $adminemail . " is not a valid email address";
if (trim($admin_email, " ") == "") {
    unset($admin_email);
} elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL) == true) {
    unset($admin_email);
    echo $invalid_mod;
};

//Makes readable version of timeout (in minutes). Do not change.
$timeout_minutes = round(($login_timeout / 60), 1);';

    fwrite($config_file, $config_text);
    fclose($config_file);

    $i++;

} catch (Exception $f) {
    echo "Failed to write config.php file" , $f->getMessage(), "\n";
}