<?php

// Verifies a users authorization
class AuthorizationHandler
{

    //Checks session keys
    private function checkSessionKey($key)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[$key])) {
            return false;
        }
        return $_SESSION[$key];
    }

    //Check if pagetype is a known value
    private function checkPageType($pagetype)
    {
        $allowed_types = array('superadminpage', 'adminpage', 'userpage', 'loginpage', 'page');
        if (!in_array($pagetype, $allowed_types, true)) {
            throw new Exception("Server Error: Please contact site administrator and relay this error - xER41400 [".$pagetype."]");
            exit;
        }
    }

    private function sessionValid()
    {
        return $_SERVER["REMOTE_ADDR"] == $this->checkSessionKey("ip_address");
    }

    public function isSuperAdmin()
    {
        return $this->checkSessionKey("superadmin") == 1 && $this->sessionValid();
    }

    public function isAdmin()
    {
        return $this->checkSessionKey("admin") != false && $this->sessionValid();
    }

    public function isLoggedIn()
    {
        return $this->checkSessionKey("username") != false && $this->sessionValid();
    }

    //Check if user is OK for $pagetype
    public function pageOk($pagetype)
    {
        $this->checkPageType($pagetype);

        if (!$this->isSuperAdmin() && $pagetype == "superadminpage") {
            return false;
        }

        if (!$this->isAdmin() && $pagetype == "adminpage") {
            return false;
        }
        
        if (!$this->isLoggedIn() && $pagetype == "userpage") {
            return false;
        }

        return true;
    }
}
