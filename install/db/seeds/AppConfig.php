<?php


use Phinx\Seed\AbstractSeed;

class AppConfig extends AbstractSeed
{
    public function run()
    {
        $data = [
                  ["setting" => "active_email", "value" => "Your new account is now active! Click this link to log in!", "sortorder" => 27, "category" => "Messages", "type" => "text", "description" => "Email message when account is verified", "required" => 1] ,
                  ["setting" => "active_msg", "value" => "Your account has been verified!", "sortorder" => 26, "category" => "Messages", "type" => "text", "description" => "Display message when account is verified", "required" => 1] ,
                  ["setting" => "admin_email", "value" => "braddmagyar@gmail.com", "sortorder" => 31, "category" => "Website", "type" => "text", "description" => "Site administrator email address", "required" => 1] ,
                  ["setting" => "admin_verify", "value" => "false", "sortorder" => 21, "category" => "Security", "type" => "boolean", "description" => "Require admin verification", "required" => 1] ,
                  ["setting" => "avatar_dir", "value" => "/user/avatars", "sortorder" => 6, "category" => "Website", "type" => "text", "description" => "Directory where user avatars should be stored inside of base site directory. Do not include base_dir path.", "required" => 1] ,
                  ["setting" => "base_dir", "value" => "/Applications/MAMP/htdocs/phplogintest", "sortorder" => 2, "category" => "Website", "type" => "hidden", "description" => "Base directory of website in filesystem. \"C:...\" in windows, \"/...\" in unix systems", "required" => 1] ,
                  ["setting" => "base_url", "value" => "http://localhost:8888/phplogintest", "sortorder" => 3, "category" => "Website", "type" => "url", "description" => "Base URL of website. Example: \"http://sitename.com\"", "required" => 1] ,
                  ["setting" => "cookie_expire_seconds", "value" => "2592000", "sortorder" => 19, "category" => "Security", "type" => "number", "description" => "Cookie expiration (in seconds)", "required" => 1] ,
                  ["setting" => "curl_enabled", "value" => "true", "sortorder" => 29, "category" => "Website", "type" => "boolean", "description" => "Enable curl for various processes such as background email sending", "required" => 1] ,
                  ["setting" => "email_working", "value" => "true", "sortorder" => 30, "category" => "Mailer", "type" => "hidden", "description" => "Indicates if email settings are correct and can connect to a mail server", "required" => 1] ,
                  ["setting" => "from_email", "value" => "braddmagyar@gmail.com", "sortorder" => 13, "category" => "Mailer", "type" => "email", "description" => "From email address. Should typically be the same as \"mail_user\" email.", "required" => 1] ,
                  ["setting" => "from_name", "value" => "Test Website", "sortorder" => 14, "category" => "Mailer", "type" => "text", "description" => "Name that shows up in \"from\" field of emails", "required" => 1] ,
                  ["setting" => "htmlhead", "value" => "<!DOCTYPE html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content-width='device-width', initial-scale='1', shrink-to-fit='no'>", "sortorder" => 4, "category" => "Website", "type" => "textarea", "description" => "Main HTML header of website (without login-specific includes and script tags). Do not close <html> tag! Will break application functionality", "required" => 1] ,
                  ["setting" => "jwt_secret", "value" => "php-login", "sortorder" => 20, "category" => "Security", "type" => "text", "description" => "Secret for JWT for tokens (Can be anything)", "required" => 1] ,
                  ["setting" => "login_timeout", "value" => "300", "sortorder" => 18, "category" => "Security", "type" => "number", "description" => "Cooloff time for too many failed logins (in seconds)", "required" => 1] ,
                  ["setting" => "mail_authtype", "value" => "PLAIN", "sortorder" => 8, "category" => "Mailer", "type" => "select", "description" => "Email authentication type", "required" => 1] ,
                  ["setting" => "mail_port", "value" => "587", "sortorder" => 12, "category" => "Mailer", "type" => "number", "description" => "Mail port. Common settings are 465 for ssl, 587 for tls, 25 for other", "required" => 1] ,
                  ["setting" => "mail_pw", "value" => "def50200a2d64500cb033d36fa85aff1668004f57b4293e40b8fbb9ab483a25002fd253a92fdeab7585f8d4218bbcb3df07ce7332712cd1df3eb04c9e1f6ae94c22e31db3b90bb94e26963f3bdf4fa643b80712885d3d8b9c34d9d5689113706fa49517b057d999a73dfbff8", "sortorder" => 10, "category" => "Mailer", "type" => "password", "description" => "Email password to authenticate mailer", "required" => 1] ,
                  ["setting" => "mail_security", "value" => "tls", "sortorder" => 9, "category" => "Mailer", "type" => "select", "description" => "Mail security type. Possible values are \"ssl\", \"tls\" or leave blank", "required" => 1] ,
                  ["setting" => "mail_sendtype", "value" => "SMTP", "sortorder" => 7, "category" => "Mailer", "type" => "select", "description" => "Method used to send mail (SMTP heavily recommended, other methods may work, but are much more likely to get sent to spam or silently fail)", "required" => 1] ,
                  ["setting" => "mail_server", "value" => "smtp.gmail.com", "sortorder" => 8, "category" => "Mailer", "type" => "text", "description" => "Mail server address. Example: \"smtp.email.com\"", "required" => 1] ,
                  ["setting" => "mail_user", "value" => "staticheroproductions@gmail.com", "sortorder" => 9, "category" => "Mailer", "type" => "email", "description" => "Email user", "required" => 1] ,
                  ["setting" => "mainlogo", "value" => "", "sortorder" => 5, "category" => "Website", "type" => "url", "description" => "URL of main site logo. Example \"http://sitename.com/logo.jpg\"", "required" => 1] ,
                  ["setting" => "max_attempts", "value" => "5", "sortorder" => 17, "category" => "Security", "type" => "number", "description" => "Maximum login attempts", "required" => 1] ,
                  ["setting" => "password_min_length", "value" => "6", "sortorder" => 16, "category" => "Security", "type" => "number", "description" => "Minimum password length if \"password_policy_enforce\" is set to true", "required" => 1] ,
                  ["setting" => "password_policy_enforce", "value" => "true", "sortorder" => 15, "category" => "Security", "type" => "boolean", "description" => "Require a mixture of upper and lowercase letters and minimum password length (set by \"password_min_length\")", "required" => 1] ,
                  ["setting" => "reset_email", "value" => "Click the link below to reset your password", "sortorder" => 28, "category" => "Messages", "type" => "text", "description" => "Email message when user wants to reset their password", "required" => 1] ,
                  ["setting" => "signup_requires_admin", "value" => "Thank you for signing up! Before you can login, your account needs to be activated by an administrator.", "sortorder" => 23, "category" => "Messages", "type" => "text", "description" => "Message displayed when user signs up, but requires admin approval", "required" => 1] ,
                  ["setting" => "signup_thanks", "value" => "Thank you for signing up! You will receive an email shortly confirming the verification of your account.", "sortorder" => 22, "category" => "Messages", "type" => "text", "description" => "Message displayed wehn user signs up and can verify themselves via email", "required" => 1] ,
                  ["setting" => "site_name", "value" => "localhost", "sortorder" => 1, "category" => "Website", "type" => "text", "description" => "Website name", "required" => 1] ,
                  ["setting" => "timezone", "value" => "Europe/Berlin", "sortorder" => 32, "category" => "Website", "type" => "timezone", "description" => "Server time zone", "required" => 1] ,
                  ["setting" => "token_validity", "value" => "24", "sortorder" => 33, "category" => "Security", "type" => "number", "description" => "Token validity in Hours (default 24 hours)", "required" => 1] ,
                  ["setting" => "verify_email_admin", "value" => "Thank you for signing up! Your account will be reviewed by an admin shortly", "sortorder" => 24, "category" => "Messages", "type" => "text", "description" => "Email message when account requires admin verification", "required" => 1] ,
                  ["setting" => "verify_email_noadmin", "value" => "Click this link to verify your new account!", "sortorder" => 25, "category" => "Messages", "type" => "text", "description" => "Email message when user can verify themselves", "required" => 1] ,
              ];

        $app_config = $this->table('app_config');
        $app_config->insert($data)
                    ->save();
    }
}
