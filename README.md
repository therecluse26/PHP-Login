PHP-Login 3.1
=============

PHP-Login is a simple login and signup system built with PHP, MySQL (with PDO), jQuery and Bootstrap 3. It is meant to be a starting point for PHP developers to build sites from and includes basic user management classes and methods.

Technologies used:
------------------
##### Prerequisites

- `PHP` *_required_*
	- Version `7.0+` recommended
	- Minimum version: `5.6`
	- `pdo_mysql` extension required
	- Recommended to enable `shell_exec`

- `MySQL` *_required_*
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
	- Version `5.0`

##### Other libraries
- `DataTables`
	- Version `1.10.16`
- `Cropper`
- `LoadingOverlay`
- `Multiselect`
	- Version `2.5.0`

##### General Recommendations

- Enable SSL on your site! [Get a free cert at LetsEncrypt](https://letsencrypt.org)
	 - Their free tool called `Certbot` makes this process virtually painless

- Linux server running Apache or Nginx is preferred

- Shell access is recommended. While it is likely possible (but unsupported) to install this library without shell access (such as on a shared web hosting provider), but it's highly recommended that you instead opt for a VPS solution or some other provider that allows you root shell access

- Run `mysql_secure_installation` on server prior to app installation

- Host your database on an encrypted filesystem

- File/directory permissions should be locked down to an appropriate level [Useful information](https://www.digitalocean.com/community/tutorials/linux-permissions-basics-and-how-to-use-umask-on-a-vps#types-of-permissions)


Installation
------------
[Automated Installation Instructions](install_automated.md)
[Manual Installation Instructions](install_manual.md)

Documentation
-------------
[Site Config Settings](site_config.md)
[API Methods](methods.md)
[Change Log](changelog.md)
