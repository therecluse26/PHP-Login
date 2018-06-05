Configuration Settings
----------------------
### `Website` tab

- `site_name` - The human-readable name of your website, e.g. "Harold's Shoelace Emporium"
- `base_url` - The base url of your website, e.g. "http://www.haroldsshoelaces.com"
- `htmlhead` - The global HTML header for your website. Necessary because of javascript libraries that are loaded in by the server. This only needs to be basic meta-information such as:
```html
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name='viewport' content-width='device-width', initial-scale='1', shrink-to-fit='no'>
```

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
