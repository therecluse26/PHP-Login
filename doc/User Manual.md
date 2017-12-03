PHP-Login 3.0
=============

PHP-Login is a simple login and signup system built with PHP, MySQL (with PDO), jQuery and Bootstrap 3. It is meant to be a starting point for PHP developers to build sites from and includes basic user management classes and methods.

Technologies used:
------------------
##### Prerequisites

- `PHP` *_required_
	- Version `7.0+` recommended
	- Minimum version: `5.5`
	- `pdo_mysql` extension required
	- Recommended to enable `shell_exec`
	
- `MySQL` *_required_
	- Version `5.6+` recommended
 
- `Composer` _recommended_
	- Version `1.2.1+` recommended
	 
	 <small>*If Composer is not installed on the system or accessible through `shell_exec`, a self-contained `composer.phar` file located in the `install` directory is used*</small>
	 
- `cURL` _recommended_
	- Version `7+` recommended

##### Components loaded via Composer
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
### Clone the Repository
    $ git clone https://github.com/fethica/PHP-Login.git

### Run through web-based installer 
Open this link in your web browser (replacing [yoursite.com] with your site address)
    
    http://[yoursite.com]/install/index.php

Select an installation option from the pop-up modal that appears: `Automated` or `Manual`

<div id="autoinstall">
<h3>Automated Install</h3>
<hr>
</div>

Enter all relevant information into the form, submit, and wait for install to complete. 

<img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/auto_install_form.png" alt="Automated Installer Form" style="display:block;margin:auto;" />

This will generate necessary database connection and configuration files, pull required `Composer` dependencies, and create/seed the database with user supplied data.

<img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/auto_install_inprogress.png" alt="Automated Installer In Progress" style="width:500px;display:block;margin:auto;" />

##### If any errors occur, or if you for some reason feel like being awesome, you may install manually. See the secton entitled [Manual Installation Instructions](#maninstall) for more information.

 <img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/auto_install_success.png" alt="Automated Installer Complete" style="width:500px;display:block;margin:auto;" />

Continue to [Post Installation Instructions](#postinstall) 


<div id="maninstall">
<h3>Manual Install</h3>
<hr>
</div>

The manual installation process is, well, more manual than the automated installer. A simple tool, however, has been provided to save time and headache in generating the necessary sql scripts, config files and providing some additional guidance.

Fill out all requested information on this page and click the `Generate Configuration` button on the bottom.

 <img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/maninstall_empty.png" alt="Manual Install Form" style="display:block;margin:auto;" />

Configuration and SQL scripts will be generated. Several manual steps will now be necessary.

- Copy/Paste and run the SQL script on the desired database server as a user with admin privileges. This script will generate the database/tables as well as all required triggers, indexes, etc.

- Copy/paste the configuration output into the `/login/dbconf.php` file. 

- Open a terminal and navigate to the site root directory that includes PHP-Login and run `composer install` to pull in required depenencies. 

 <img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/maninstall_filled.png" alt="Manual Install Generated Code" style="display:block;margin:auto;" />

Installation is now complete, however, a few steps are still necessary before your site is functional. Proceed to [Post Installation Instructions](#postinstall) 

	
<div id="postinstall"></div>

Post Installation
-----------------
Now that basic installation is completed, we will need to login and do some simple site configuration. Navigate to the root of your site to login under the superadmin account you just created: ex: `[your_site]/login/index.php`

 <img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/loginfrm_empty.png" alt="Log in" style="display:block;margin:auto;" />

Once you are signed in for the first time under your superadmin account, we need to finish editing site configuration. Click on the top right corner of your screen where your username is located and select `Edit Site Config` to continue.

 <img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/dropdown_menu.png" alt="Edit Site Config" style="height:200px;display:block;margin:auto;"/>

On the `Edit Site Configuration` page, numerous configuration options can be set. Be aware, that some of these changes (such as `base_url`) can lead to a broken site if configured incorrectly. If any of these config changes do lead to a non-functioning site, you can recover it by updating the `app_config` MySQL database table to the correct values.

For baseline functionality, ensure that proper SMTP settings are configured in the `Mailer` tab. Once this is filled out, click `Save` and then `Test Email Config` to show if a successful email connection was made or otherwise show connection/authentication errors.

 <img src="https://raw.githubusercontent.com/fethica/PHP-Login/dev/doc/images/superadmin_email_config.png" alt="Mail Config" style="display:block;margin:auto;" />


*_For quick reference, hover over the name of each setting to see a description of what it does_


Verify that everything is working properly. Once this is done **remember to delete the `/install` directory**

#### Congratulations, you've successfully set up PHP-Login! Now get to building your website!

To learn about additional features, open the corner dropdown menu and explore the options contained. 

**Note:** The available options will be different if a user is an admin vs a standard user. Standard users will only see the `Edit Profile`, `Account Settings` and `Logout` menu options.


<div id="postinstall"></div>

Configuration Settings
----------------------
### `Website` tab

- `site_name` - The human-readable name of your website, e.g. "Harold's Shoelace Emporium"
- `base_url` - The base url of your website, e.g. "http://www.harolds-shoelaces.com"
- `htmlhead` - The global HTML header for your website. Necessary because of javascript libraries that are loaded in by the server. This only needs to be basic meta-information such as:
``<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name='viewport' content-width='device-width', initial-scale='1', shrink-to-fit='no'>``
	
- `mainlogo` - URL of the main site logo that will appear in the top left corner of your navbar by default
- `avatar_dir` - Filesystem directory under your PHP-Login base directory to which user avatars will be stored
- `curl_enabled` - Tells PHP-Login if `curl` is enabled on your system for sending emails in batches
- `admin_email` - Email address of superadmin in case of errors
- `timezone` - Timezone of website

### `Mailer` tab
- 	`mail_server_type` - Type of mail server. `smtp` is default and the only tested value.
-  `mail_server` - Mail server address. Ex: `stmp.website.com`
-  `mail_user` - Email server user. Ex: `user@website.com`
-  `mail_pw` - Password of email server user
-  `mail_security` - Type of email encryption for server. `tls` and `ssl` available
-  `mail_port` - Port of email server
-  `from_email` - Email address to send system emails from
-  `from_name` - Name for system to send emails as
	
### `Security` tab
- `password_policy_enforce` - If you want to require the password policy that you set, select `true`
- `password_min_length` - Minimum password length if `password_policy_enforce` is set to `true`
-  `max_attempts` - Maximum number of login attempts before locking user out for set `login_timeout` value
-  `login_timeout` - Number of seconds to lock a user out for after `max_attempts` is exceeded
-  `cookie_expire_seconds` - Number of seconds before cookies expire
-  `jwt_secret` - Secret for JSON Web Tokens. Used to generate token hashes, can be any value
-  `admin_verify` - If set to `true` admin must verify users. If set to false, users can self-verify via email
	
### `Messages` tab
- `signup_thanks` - Message to display after user signs up and can self verify. Should notify user that a verification email will be sent
- `signup_requires_admin` - Message to display after user signs up but needs admin approval
- `verify_email_admin` - Email sent to user when admin verification is required
- `verify_email_noadmin` - Email sent to user for self-verification
- `active_msg` - Message displayed when account is successfully verified
- `active_email` - Email sent to user confirming account verification
- `reset_email` - Email sent to user when password reset is requested