<?php
// Extend this class to re-use db connection
class DbConn
{
    public $conn;
    public function __construct()
    {
        $up_dir = realpath(__DIR__ . '/..');

        if (file_exists('dbconf.php')) {
            require 'dbconf.php';
        } else {
            require $up_dir.'/dbconf.php';
        }

        $this->host = $host; // Host name
        $this->username = $username; // Mysql username
        $this->password = $password; // Mysql password
        $this->db_name = $db_name; // Database name
        $this->tbl_prefix = $tbl_prefix; // Prefix for all database tables
        $this->tbl_members = $tbl_members;
        $this->tbl_memberinfo = $tbl_memberinfo;
        $this->tbl_admins = $tbl_admins;
        $this->tbl_attempts = $tbl_attempts;
        $this->tbl_deleted = $tbl_deleted;
        $this->tbl_tokens = $tbl_tokens;
        $this->tbl_cookies = $tbl_cookies;

        // Connect to server and select database
        try {

            $this->conn = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}
