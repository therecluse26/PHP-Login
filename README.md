PHP-Login
=========

A simple login system with PHP, MySQL and jQuery (AJAX) using Bootstrap 3 for the form design.

<img src="https://github.com/fethica/PHP-Login/raw/master/images/screenshot.png" alt="Login Page Screenshot" />

### Creating the Database

Create database "login" and create table "members" :

```sql
CREATE TABLE `members` (
`id` int(4) NOT NULL auto_increment,
`username` varchar(65) NOT NULL default '',
`password` varchar(65) NOT NULL default '',
PRIMARY KEY (`id`)
);

INSERT INTO `members` VALUES (1, 'fethi', '1234');
```

### Setup the `config.php` file :

```php
<?php
	$host="localhost"; // Host name 
	$username="root"; // Mysql username 
	$password="root"; // Mysql password 
	$db_name="login"; // Database name 
	$tbl_name="members"; // Table name
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
