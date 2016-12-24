<?php
class MailConf
{
    public function __construct()
    {
        $up_dir = realpath(__DIR__ . '/..');

        if(file_exists($up_dir.'/config.php')) {
            require $up_dir.'/config.php';
        } else {
            require 'config.php';
        }

        $this->base_dir = $base_dir;
        $this->base_url = $base_url;
        $this->site_name = $site_name;
        $this->mail_server_type = $mail_server_type;
        $this->smtp_server = $smtp_server;
        $this->smtp_user = $smtp_user;
        $this->smtp_pw = $smtp_pw;
        $this->smtp_port = $smtp_port;
        $this->smtp_security = $smtp_security;
        $this->from_email = $from_email;
        $this->from_name = $from_name;
        $this->admin_verify = $admin_verify;
        $this->active_email = $active_email;
        $this->signin_url = $signin_url;
        $this->verify_email = $verify_email;
        $this->mainlogo = $mainlogo;
    }
}
