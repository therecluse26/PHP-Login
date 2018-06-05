<?php
namespace PHPLogin;

class CSRFHandler
{
    /**
    * Handles CSRF protection for ajax and other requests
    */
    public $token;

    public function __construct()
    {
        if (isset($_SESSION['csrf_token'])) {
            $this->token = $_SESSION['csrf_token'];
        } else {
            $this->token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function generate_meta_tag()
    {
        return "<meta name='csrf_token' value='{$this->token}'>";
    }

    public function valid_token()
    {
        return (isset($_POST['csrf_token']) && ($_POST['csrf_token'] == $this->token)) ||
               (isset($_GET['csrf_token']) && ($_GET['csrf_token'] == $this->token));
    }
}
