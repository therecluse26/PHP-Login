<?php
/**
* PHPLogin\LoginHandler extends AppConfig
*/
namespace PHPLogin;

/**
* Login handling functions
*
* Contains all methods used in login form as well as session initialization and logout
*/
class LoginHandler extends AppConfig
{

    /**
     * Checks user login
     *
     * @param  string  $username   Username
     * @param  string  $password   Password
     * @param  boolean $cookie     User is creating cookie on login
     *
     * @return string
     */
    public function checkLogin($username, $password, bool $cookie = false)
    {
        $ip_address = $_SERVER["REMOTE_ADDR"];
        $login_timeout = (int)$this->login_timeout;
        $max_attempts = (int)$this->max_attempts;
        $attcheck = $this->checkAttempts($username);
        $curr_attempts = $attcheck['attempts'];

        $datetimeNow = date("Y-m-d H:i:s");
        $oldTime = strtotime($attcheck['lastlogin']);
        $newTime = strtotime($datetimeNow);
        $timeDiff = $newTime - $oldTime;

        $timeout_minutes = round(($login_timeout / 60), 1);

        try {
            $err = '';

            $stmt = $this->conn->prepare("SELECT id, username, email, password, verified, banned
                                      FROM ".$this->tbl_members." WHERE username = :myusername");
            $stmt->bindParam(':myusername', $username);
            $stmt->execute();

            // Gets query result
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);


            if ($curr_attempts >= $max_attempts && $timeDiff < $login_timeout) {

            //Too many failed attempts
                $success = "<div class=\"alert alert-danger alert-dismissable\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                          &times;
                        </button>
                        Maximum number of login attempts exceeded... please wait ".$timeout_minutes." minutes before logging in again
                      </div>";
            } else {

             //If max attempts not exceeded, continue
                // Checks password entered against db password hash
                if (PasswordHandler::checkPw($password, $result['password']) && $result['verified'] == '1' && $result['banned'] == '0') {
                    //Success! Register $username, $password and return "true"
                    $success = 'true';

                    $this->init_session($result, $cookie);
                } elseif (PasswordHandler::checkPw($password, $result['password']) && $result['verified'] == '1' && $result['banned'] == '1') {
                    //Account banned
                    $uid = $result['id'];

                    $bstmt = $this->conn->prepare("SELECT hours_remaining FROM `vw_banned_users` WHERE user_id = :uid;");
                    $bstmt->bindParam(':uid', $uid);
                    $bstmt->execute();
                    $bresult = $bstmt->fetch(\PDO::FETCH_ASSOC);

                    if ($bresult['hours_remaining'] <= 0) {
                        $uid = $result['id'];

                        // Delete from member_jail, update banned record in members table and allow login
                        $bstmt = $this->conn->prepare("DELETE FROM ".$this->tbl_member_jail." WHERE user_id = :uid;");
                        $bstmt->bindParam(':uid', $uid);
                        $bstmt->execute();

                        $bstmt = $this->conn->prepare("UPDATE ".$this->tbl_members." SET banned = 0 WHERE id = :uid;");
                        $bstmt->bindParam(':uid', $uid);
                        $bstmt->execute();

                        $success = 'true';

                        $this->init_session($result, $cookie);
                    } else {
                        $success = "<div class=\"alert alert-danger alert-dismissable\">
                                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                                    &times;
                                  </button>
                                  Your account is currently suspended
                                </div>";
                    }
                } elseif (PasswordHandler::checkPw($password, $result['password']) && $result['verified'] == '0') {

                //Account not yet verified
                    $success = "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                                &times;
                              </button>
                              Your account has been created, but you cannot log in until it has been verified
                            </div>";
                } else {

                //Wrong username or password
                    $success = "<div class=\"alert alert-danger alert-dismissable\">
                              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                                &times;
                              </button>
                              Wrong Username or Password
                            </div>";
                }
            }

            return $success;
        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();
            return $err;
        }
    }

    /**
     * Checks `attempts` table when a login is attempted
     *
     * @param  string $username Username
     *
     * @return void
     */
    public function checkAttempts($username)
    {
        try {
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $err = '';

            $sql = "SELECT Attempts as attempts, lastlogin FROM ".$this->tbl_attempts." WHERE IP = :ip and Username = :username";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ip', $ip_address);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result;

            $oldTime = strtotime($result['lastlogin']);
            $newTime = strtotime($datetimeNow);
            $timeDiff = $newTime - $oldTime;
        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }

        //Determines returned value ('true' or error code)
        $resp = ($err == '') ? $result : $err;

        //return $resp;
    }

    /**
     * Inserts login attempt into the `login_attempts` table
     *
     * @param  string $username Username
     *
     * @return string
     */
    public function insertAttempt($username)
    {
        try {
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $login_timeout = (int)$this->login_timeout;
            $max_attempts = (int)$this->max_attempts;

            $datetimeNow = date("Y-m-d H:i:s");
            $attcheck = $this->checkAttempts($username);
            $curr_attempts = $attcheck['attempts'];

            $stmt = $this->conn->prepare("INSERT INTO ".$this->tbl_attempts." (ip, attempts, lastlogin, username) values(:ip, 1, :lastlogin, :username)");
            $stmt->bindParam(':ip', $ip_address);
            $stmt->bindParam(':lastlogin', $datetimeNow);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $curr_attempts++;
            $err = '';
        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }

        //Determines returned value ('true' or error code)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;
    }

    /**
     * Updates login attempt row in the `login_attempts` table
     *
     * @param  string $username Username
     *
     * @return string
     */
    public function updateAttempts($username)
    {
        try {
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $login_timeout = (int)$this->login_timeout;
            $max_attempts = (int)$this->max_attempts;

            $attcheck = $this->checkAttempts($username);
            $curr_attempts = $attcheck['attempts'];

            $datetimeNow = date("Y-m-d H:i:s");
            $oldTime = strtotime($attcheck['lastlogin']);
            $newTime = strtotime($datetimeNow);
            $timeDiff = $newTime - $oldTime;

            $err = '';
            $sql = '';

            if ($curr_attempts >= $max_attempts && $timeDiff < $login_timeout) {
                if ($timeDiff >= $login_timeout) {
                    $sql = "UPDATE ".$this->tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts = 1;
                }
            } else {
                if ($timeDiff < $login_timeout) {
                    $sql = "UPDATE ".$this->tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts++;
                } elseif ($timeDiff >= $login_timeout) {
                    $sql = "UPDATE ".$this->tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts = 1;
                }

                $stmt2 = $this->conn->prepare($sql);
                $stmt2->bindParam(':attempts', $curr_attempts);
                $stmt2->bindParam(':ip', $ip_address);
                $stmt2->bindParam(':lastlogin', $datetimeNow);
                $stmt2->bindParam(':username', $username);
                $stmt2->execute();
            }
        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }

        //Determines returned value ('true' or error code) (ternary)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;
    }

    /**
    * Initializes session
    *
    * @param array $result  Array of session values to set
    * @param boolean  $cookie  If session includes a cookie or not
    *
    * @return void
    */
    public function init_session($result, bool $cookie)
    {
        session_start();

        $_SESSION['uid'] = $result['id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['ip_address'] = getenv('REMOTE_ADDR');
        $_SESSION['verified'] = $result['verified'];

        if ($cookie == true) {
            //Creates cookie
            CookieHandler::initializeCookie();
        }
    }

    /**
    * Logs user out and destroys session
    */
    public static function logout()
    {
        if (isset($_COOKIE["usertoken"])) {
            setcookie("usertoken", "", time() - 10000, "/");
        }
        session_start();
        session_destroy();
    }
}
