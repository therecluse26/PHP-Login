<?php
/**
* Database connection class. This base class is extended or utilized by numerous other classes.
*/
class DbConn
{
    /**
    * Database name
    * @var string
    */
    private $db_name;
    /**
    * Database server hostname
    * @var string
    */
    private $host;
    /**
    * Database username
    * @var string
    */
    private $username;
    /**
    * Database password
    * @var string
    */
    private $password;
    /**
    * PDO Connection object
    * @var object
    */
    public $conn;
    /**
     * Database Table Prefix
     * @var string
     */
    public $tbl_prefix;
    /**
    * Table where basic user data is stored
    * @var string
    */
    public $tbl_members;
    /**
    * Table where user profile info is stored
    * @var string
    */
    public $tbl_memberinfo;
    /**
    * Admin table
    * @var string
    */
    public $tbl_admins;
    /**
    * Table where login attempts are logged
    * @var string
    */
    public $tbl_attempts;
    /**
    * Table where deleted users are stored temporarily
    * @var string
    */
    public $tbl_deleted;
    /**
    * Table that JWT tokens are validated against
    * @var string
    */
    public $tbl_tokens;
    /**
    * Table that cookies are stored and validated against
    * @var string
    */
    public $tbl_cookies;
    /**
    * Table where main application configuration is stored
    * @var string
    */
    public $tbl_appConfig;
    /**
    * Table where mail send logs are stored
    * @var string
    */
    public $tbl_mailLog;
    /**
    * Makes this a singleton class
    * @var Singleton
    */
    protected static $instance;

    public function __construct()
    {
    /**
    * Pulls tables from
    **/
        $up_dir = realpath(__DIR__ . '/..');

        if (file_exists('dbconf.php')) {
            require 'dbconf.php';
        } else {
            require $up_dir.'/dbconf.php';
        }
        $this->tbl_prefix = $tbl_prefix;
        $this->tbl_members = $tbl_members;
        $this->tbl_memberinfo = $tbl_memberinfo;
        $this->tbl_admins = $tbl_admins;
        $this->tbl_attempts = $tbl_attempts;
        $this->tbl_deleted = $tbl_deleted;
        $this->tbl_tokens = $tbl_tokens;
        $this->tbl_cookies = $tbl_cookies;
        $this->tbl_appConfig = $tbl_appConfig;
        $this->tbl_mailLog = $tbl_mailLog;

        // Connect to server and select database
        try {
            $this->conn = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            die($e->getMessage());

        }
    }
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;

    }
    /**
    * Prevents cloning
    * @return void
    **/
    private function __clone()
    {
    }
    /**
    * Prevents unserialization
    * @return void
    **/
    private function __wakeup()
    {
    }
}
