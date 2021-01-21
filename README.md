[![Project Status: Abandoned – Initial development has started, but there has not yet been a stable, usable release; the project has been abandoned and the author(s) do not intend on continuing development.](https://www.repostatus.org/badges/latest/abandoned.svg)](https://www.repostatus.org/#abandoned)


PHP-Login
=========
**Version 3.1**

`PHP-Login` is a simple login and signup system built with PHP, MySQL (with PDO), jQuery and Bootstrap 3. It is meant to be a starting point for PHP developers to build sites from and includes basic user management classes and methods.


Technologies used:
------------------
##### Prerequisites

- `PHP` *_required_*
	- Minimum version: `7.0`
	- `pdo_mysql` extension required
	- Recommended to enable `shell_exec`

- `MySQL` *_required_*
	- Version `5.6+` recommended

- `Composer` *_required_*
	- Version `1.2.1+` recommended
	- `mbstring` and `dom` php extensions required

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
	 - Their free tool [Certbot](https://certbot.eff.org) makes this process virtually painless

- Linux server running [Apache](https://www.apache.org) or [Nginx](https://nginx.org) is preferred

- Shell access is recommended. While it is likely possible to install this library without shell access (such as on a shared web hosting provider), this is unsupported. It's highly recommended that you instead opt for a VPS provider such as [DigitalOcean](https://m.do.co/c/da6f17522df3) that allows you root shell access

- Run `mysql_secure_installation` on server prior to app installation

- Host your database on an encrypted filesystem

- File/directory permissions should be locked down to an appropriate level
	- [Useful information](https://www.digitalocean.com/community/tutorials/linux-permissions-basics-and-how-to-use-umask-on-a-vps#types-of-permissions)

Installation
------------

#### Clone the Repository
	$ git clone https://github.com/therecluse26/PHP-Login.git

#### Install necessary dependencies with `Composer`
	$ composer install --no-dev

#### Run through web-based installer
Open this link in your web browser (replacing [yoursite.com] with your site address)

    http://{yoursite.com}/install/index.php

Select an installation option from the pop-up modal that appears: `Automated` or `Manual`

***NOTE*** ** If you are upgrading from a prior version of PHP-Login (>3.1), you should install this version as new and then navigate to the `/install/legacymigration/index.php` page to migrate your existing data to the new application version (to reflect schema updates) **

[Automated Installation Instructions](docs/install_automated.md)

[Manual Installation Instructions](docs/install_manual.md)

Documentation
-------------
[Site Config Settings](docs/site_config.md)

[API Methods](docs/methods.md)

\* *Full API documentation can be found by nagivating to:* `{yoursite.com}/docs/api/index.html`

[Change Log](docs/changelog.md)
