PHP-Login
=========

A simple, secure login system with PHP, MySQL and jQuery (AJAX) using Bootstrap 3 for the form design.

<img src="https://github.com/fethica/PHP-Login/raw/master/images/screenshot.png" alt="Login Page Screenshot" />

Credit to <a href="https://github.com/Synchro">Synchro</a> for <a href="https://github.com/Synchro/PHPMailer">PHPMailer</a>, used in new user verification

### Creating the Database

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

### Setup the `config.php` file :

```php
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
	$from_email = 'test@test.net'; //Webmaster email
	$from_name = 'Test Email'; //"From name" displayed on email
	//HTML Messages shown before URL in emails (the more 
	$verifymsg = 'Click this link to verify your new account!<br>'; //Verify email message
	$active_email = 'Your new account is now active! Click the link below to log in!<br>';//Active email message
	//LOGIN FORM RESPONSE MESSAGES/ERRORS
	$signupthanks = 'Thank you for signing up! You will receive an email shortly confirming the verification of your account.';
	$activemsg = 'Your account has been verified! You may now login at <br><a href="'.$signin_url.'">'.$signin_url.'</a>';
?>
```

### Check the Username and the Password using jQuery (Ajax) :

If the user has the right username and password, then the `checklogin.php` will send 'true', register the username and the password in a session, and redirect to `login_success.php`.
If the username and/or the password are wrong the `checklogin.php` will send "Wrong Username or Password".

In the `login.js` file :

```javascript
$(document).ready(function(){

  //When the user click on the login button    
  $("#submit").click(function(){
    
    //Get each input value in a veriable.
    var username = $("#myusername").val();
    var password = $("#mypassword").val();
    
    //Check if the username and/or the password input are empty.
    if((username == "") || (password == "")) {
      $("#message").html("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter a username and a password</div>");
    }
    else {
      $.ajax({
        type: "POST",
        url: "checklogin.php",
        data: "myusername="+username+"&mypassword="+password,
        success: function(html){    
          if(html=='true') { //if the return value = 'true' then redirect to 'login_success.php
            window.location="login_success.php";
          }
          else { //if the return value != 'true' then add the error message to the div.#message
            $("#message").html(html);
          }
        },
        beforeSend:function()
        { //loading gif 
          $("#message").html("<p class='text-center'><img src='images/ajax-loader.gif'></p>")
        }
      });
    }
    return false;
  });
});
```
