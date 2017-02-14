PHP-Login Manual
================

PHP-Login is a simple, secure login and signup system built with vanilla PHP, MySQL (with PDO) and jQuery using Bootstrap 3 for the visual frameworks. It is meant to be a starting point for PHP developers to build sites off of and includes basic user management classes and methods.

Technologies used:
------------------
#####Prerequisites

- `PHP` *_required_
	- Version `7.0+` recommended
	- Minimum version: `5.5`
	- `pdo_mysql` extension required
	- Recommended to enable `shell_exec`
	
- `MySQL` *_required_
	- Version `5.6+` recommended
 
- `Composer` *_recommended_
	- Version `1.2.1+` recommended
	 
	 <small>*If Composer is not installed on the system or accessible through `shell_exec`, a self-contained `composer.phar` file located in the `install` directory is used*</small>
	 
- `cURL` *_recommended_
	- Version `7+` recommended

#####Components loaded via Composer
- `jQuery`
	- Version `3.1`
	- Pulled in via composer
- `Bootstrap`
	- Version `^3`
- `PHP-Mailer`
	- Version `5.2`
- `JSON Web Tokens` (JWT) (Firebase implementation)
	- Version `4.0`


Installation
------------
### Clone the Repository (dev branch)
    $ git clone -b dev https://github.com/fethica/PHP-Login.git

### Run through web-based installer 
Open this link in your web browser (replacing [yoursite.com] with your site address)
    
    http://[yoursite.com]/install/index.php

Enter all relevant information into the form, submit, and wait for install to complete.


#####If any errors occur, or if you for some reason feel like being awesome, you may install manually. See the secton entitled [Manual Installation Instructions](#maninstall) for more information.


Once installation is complete, click the login link to sign in under your superadmin account and finish editing site configuration. Hover over the name of each setting to see a description.




<div id="maninstall">
<h2>Manual Installation Instructions</h2>
</div>
There are several manual steps that will need to be taken to get PHP-Login installed if for some reason the installer fails. 

- Create the MySQL database
```sql
CREATE DATABASE login;
```

	- Ensure that you have root access, or otherwise have the following privileges enabled: `SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, EVENT, INDEX, REFERENCES`

	- Once the database installation process is finished, you should create a new user with more restricted privileges for the application to use. The minimal privileges needed are `SELECT, INSERT, UPDATE, DELETE, TRIGGER`
	- For more information on MySQL privileges, see here: <a href="https://dev.mysql.com/doc/refman/5.7/en/privileges-provided.html">https://dev.mysql.com/doc/refman/5.7/en/privileges-provided.html</a>

- Create database tables, triggers, etc.
	- 