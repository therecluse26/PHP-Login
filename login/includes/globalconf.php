<?php
class GlobalConf
{
    public $conf;
    public static $attempts;
    public function __construct()
    {
        require 'config.php';
        $this->ip_address = $ip_address;
        $this->login_timeout = $login_timeout;
        $this->timeout_minutes = $timeout_minutes;
        $this->base_url = $base_url;
        $this->signin_url = $signin_url;
        $this->max_attempts = $max_attempts;
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
