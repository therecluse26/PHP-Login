<?php
class GlobalConf
{
    public static $attempts;
    public function __construct()
    {
        $up_dir = realpath(__DIR__ . '/..');

        if(file_exists($up_dir.'/config.php')) {
            require $up_dir.'/config.php';
        } else {
            require 'config.php';
        }
        $this->site_name = $site_name;
        $this->ip_address = $ip_address;
        $this->login_timeout = $login_timeout;
        $this->timeout_minutes = $timeout_minutes;
        $this->base_dir = $base_dir;
        $this->base_url = $base_url;
        $this->signin_url = $signin_url;
        $this->max_attempts = $max_attempts;
        $this->jwt_secret = $jwt_secret;
        $this->activemsg = $activemsg;
        $this->signupthanks = $signupthanks;
        $this->htmlhead = $htmlhead;
        $this->pwpolicy = $password_policy_enforce;
        $this->pwminlength = $password_min_length;
        $this->avatar_dir = $avatar_dir;
     }

    public function addAttempt()
    {
        $attempts++;
    }
    public function resetAttempts()
    {
        $attempts = 0;
    }
}
