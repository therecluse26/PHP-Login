PHP-Login
=========

A simple, secure login and signup system with PHP, MySQL and jQuery (AJAX) using Bootstrap 3 for the form design as well as PHP-Mailer for user account verification and confirmation

<img src="https://raw.githubusercontent.com/fethica/PHP-Login/master/login/images/screenshot.png" alt="Login Page Screenshot" />

## Installation
### Clone the Repository (recursively to include PHP-Mailer submodule)
    $ git clone --recursive https://github.com/fethica/PHP-Login.git

### Creating the MySQL Database

Create database "login" and create table "members" :

```sql
CREATE TABLE `members` (
  `id` char(23) NOT NULL,
  `username` varchar(65) NOT NULL DEFAULT '',
  `password` varchar(65) NOT NULL DEFAULT '',
  `email` varchar(65) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `password_UNIQUE` (`password`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```

### Setup the `config.php` file
<i>Read code comments for a description of each variable</i>

```php
<?php
	//DATABASE CONNECTION VARIABLES
	$host = "localhost"; // Host name
	$username = "root"; // Mysql username
	$password = "root"; // Mysql password
	$db_name = "login"; // Database name
	$tbl_name = "members"; // Table name

	//Set this for global site use
	$site_name = 'Your Site Name';

	//ONLY set this if you want a moderator to verify users and not the users themselves, otherwise leave blank or comment out
	$admin_email = '';

	//EMAIL SETTINGS
	//SEND TEST EMAILS THROUGH FORM TO https://www.mail-tester.com GENERATED ADDRESS FOR SPAM RATING AND TIPS
	$from_email = 'your_email@test.com'; //Webmaster email
	$from_name = 'Your Email Name'; //"From name" displayed on email

	//Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
	$mailServerType = 'smtp';
	//IF $mailServerType = 'smtp'
	$smtp_server = 'smtp.test.com';
	$smtp_user = 'your_email@test.com';
	$smtp_pw = 'your_password';
	$smtp_port = 465; //465 for ssl, 587 for tls, 25 for other
	$smtp_security = 'ssl';//ssl, tls or ''

	//HTML Messages shown before URL in emails (the more
	$verifymsg = 'Click this link to verify your new account!'; //Verify email message
	$active_email = 'Your new account is now active! Click this link to log in!';//Active email message
	//LOGIN FORM RESPONSE MESSAGES/ERRORS
	$signupthanks = 'Thank you for signing up! You will receive an email shortly confirming the verification of your account.';
	$activemsg = 'Your account has been verified! You may now login at <br><a href="'.$signin_url.'">'.$signin_url.'</a>';

	//IGNORE CODE BELOW THIS
```
### Place this code (from `index.php`) at the head of each page :
```php
<?php
  session_start();
//PUT THIS HEADER ON TOP OF EACH UNIQUE PAGE
  if(!isset($_SESSION['username'])){
    header("location:login/main_login.php");
  }
?>
```

### Check the Username and the Password using jQuery (Ajax) :

If the user has the right username and password, then the `checklogin.php` will send 'true', register the username and the password in a session, and redirect to `login_success.php`.
If the username and/or the password are wrong the `checklogin.php` will send "Wrong Username or Password".


###Signup/Login Workflow:
> 1) Create new user using `signup.php` form   
> (note: validation occurs both client and server side)    
> &nbsp;&nbsp;&nbsp;&nbsp;<b>Validation requires: </b>   
> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Passwords to match and be at least 4 characters    
> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Valid email address     
> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Unique username  
> 2) Password gets hashed and new GUID is generated for User ID   
> 3) User gets added to database as unverified  
> 4) Email is sent to user email (or $admin_email if set) with verification link   
> 5) User (or admin) clicks verification link which sends them to `verifyuser.php` and verifies user in the database    
> 6) Verified user may now log in
