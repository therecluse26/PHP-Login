<?php
/**
* Contains all methods used in login form
**/
class LoginHandler extends AppConfig
{
    /**
    * Checks user login
    **/
    public function checkLogin($myusername, $mypassword, $cookie = 0)
    {
        $ip_address = $_SERVER["REMOTE_ADDR"];
        $login_timeout = (int)$this->login_timeout;
        $max_attempts = (int)$this->max_attempts;
        $attcheck = $this->checkAttempts($myusername);
        $curr_attempts = $attcheck['attempts'];

        $datetimeNow = date("Y-m-d H:i:s");
        $oldTime = strtotime($attcheck['lastlogin']);
        $newTime = strtotime($datetimeNow);
        $timeDiff = $newTime - $oldTime;

        $timeout_minutes = round(($login_timeout / 60), 1);

        try {

            $tbl_members = $this->tbl_members;
            $err = '';

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }

        $stmt = $this->conn->prepare("SELECT id, username, email, password, verified, admin FROM ".$tbl_members." WHERE username = :myusername");
        $stmt->bindParam(':myusername', $myusername);
        $stmt->execute();

        // Gets query result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($curr_attempts >= $max_attempts && $timeDiff < $login_timeout) {

            //Too many failed attempts
            $success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Maximum number of login attempts exceeded... please wait ".$timeout_minutes." minutes before logging in again</div>";

        } else {

             //If max attempts not exceeded, continue
            // Checks password entered against db password hash
            if (PasswordCrypt::checkPw($mypassword, $result['password']) && $result['verified'] == '1') {

                //Success! Register $myusername, $mypassword and return "true"
                $success = 'true';

                session_start();

                $_SESSION['uid'] = $result['id'];
                $_SESSION['admin'] = $result['admin'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['ip_address'] = getenv ('REMOTE_ADDR');
                $_SESSION['verified'] = $result['verified'];

                if ($result['admin'] == 1){
                    $admin = UserData::pullAdmin($_SESSION['uid']);
                    $_SESSION['superadmin'] = $admin['superadmin'];
                }

                if ($cookie == 1) {
                    //Creates cookie
                    include_once $this->base_dir."/login/ajax/cookiecreate.php";
                }

            } elseif (PasswordCrypt::checkPw($mypassword, $result['password']) && $result['verified'] == '0') {

                //Account not yet verified
                $success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Your account has been created, but you cannot log in until it has been verified</div>";

            } else {

                //Wrong username or password
                $success = "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Wrong Username or Password</div>";

            }
        }
        return $success;
    }
    /**
    * Checks `attempts` table when a login is attempted
    **/
    public function checkAttempts($username)
    {
        try {
            $tbl_attempts = $this->tbl_attempts;
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $err = '';

            $sql = "SELECT Attempts as attempts, lastlogin FROM ".$tbl_attempts." WHERE IP = :ip and Username = :username";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ip', $ip_address);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;

            $oldTime = strtotime($result['lastlogin']);
            $newTime = strtotime($datetimeNow);
            $timeDiff = $newTime - $oldTime;

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }

        //Determines returned value ('true' or error code)
        $resp = ($err == '') ? $result : $err;

        //return $resp;

    }

    public function insertAttempt($username)
    {
        try {
            $tbl_attempts = $this->tbl_attempts;
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $login_timeout = (int)$this->login_timeout;
            $max_attempts = (int)$this->max_attempts;

            $datetimeNow = date("Y-m-d H:i:s");
            $attcheck = $this->checkAttempts($username);
            $curr_attempts = $attcheck['attempts'];

            $stmt = $this->conn->prepare("INSERT INTO ".$tbl_attempts." (ip, attempts, lastlogin, username) values(:ip, 1, :lastlogin, :username)");
            $stmt->bindParam(':ip', $ip_address);
            $stmt->bindParam(':lastlogin', $datetimeNow);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $curr_attempts++;
            $err = '';

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }

        //Determines returned value ('true' or error code)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;

    }

    public function updateAttempts($username)
    {
        try {
            $tbl_attempts = $this->tbl_attempts;
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

                    $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts = 1;

                }

            } else {

                if ($timeDiff < $login_timeout) {

                    $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts++;

                } elseif ($timeDiff >= $login_timeout) {

                    $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts = 1;

                }

                $stmt2 = $this->conn->prepare($sql);
                $stmt2->bindParam(':attempts', $curr_attempts);
                $stmt2->bindParam(':ip', $ip_address);
                $stmt2->bindParam(':lastlogin', $datetimeNow);
                $stmt2->bindParam(':username', $username);
                $stmt2->execute();

            }

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }

        //Determines returned value ('true' or error code) (ternary)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;

    }
    public static function logout(){

        if (isset($_COOKIE["usertoken"])) {
            setcookie("usertoken", "", time() - 10000, "/");
        }
        session_start();
        session_destroy();
    }
}
