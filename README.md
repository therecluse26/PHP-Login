PHP-Login
=========

A simple login and register system with PHP, MySQL and jQuery (AJAX) using Bootstrap 3 for the form design.

<img src="https://github.com/SainagShetty/PHP-Login/raw/master/images/screenshot.png" alt="Login-Register Page Screenshot" />

### Creating the Database

Create database "login" and create table "members" :

```sql
CREATE DATABASE `login`;

USE DATABASE `login`;

CREATE TABLE `members` (
`id` int(4) NOT NULL auto_increment,
`username` varchar(65) NOT NULL default '',
`password` varchar(65) NOT NULL default '',
`email` varchar(65) NOT NULL default '',
PRIMARY KEY (`id`)
);

```

### Setup the `config.php` file :

```php
<?php
	$host="localhost"; // Host name 
	$username="root"; // Mysql username 
	$password="root"; // Mysql password 
	$db_name="login"; // Database name 
	$tbl_name="members"; // Table name
  $salt = "SOME_STRING" //for SHA1 password hashing
?>

```
