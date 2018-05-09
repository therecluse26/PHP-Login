<?php
/**
* Verifies a user's authorization
*/
class AuthorizationHandler extends RoleHandler
{
    protected $adminroles = ['Admin', 'Superadmin'];
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

    private function sessionValid()
    {
        return $_SERVER["REMOTE_ADDR"] == $this->checkSessionKey("ip_address");
    }

    // Role gates
    public function hasRole($rolename)
    {
        switch ($rolename) {
          case null:
            return true;
          case 'loginpage':
            return true;
          case 'Admin':
            return $this->isAdmin();
          case 'Superadmin':
            return $this->isSuperAdmin();
          default:
            return ($this->checkRole($this->checkSessionKey("uid"), $rolename) != false || $this->isAdmin()) && $this->sessionValid();
        }
    }

    public function isSuperAdmin()
    {
        return $this->checkRole($this->checkSessionKey("uid"), "Superadmin") != false && $this->sessionValid();
    }

    public function isAdmin()
    {
        return ($this->checkRole($this->checkSessionKey("uid"), "Admin") != false || $this->isSuperAdmin()) && $this->sessionValid();
    }

    public function isLoggedIn()
    {
        return $this->checkSessionKey("username") != false && $this->sessionValid();
    }
}
