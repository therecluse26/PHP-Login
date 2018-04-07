<?php
/**
* Password-related methods
*/
class PasswordHandler extends DbConn
{
    public static function encryptPw($password)
    {
        $pwresp = password_hash($password, PASSWORD_DEFAULT);
        return $pwresp;
    }

    public static function checkPw($userpassword, $dbpassword)
    {
        $pwresp = password_verify($userpassword, $dbpassword);
        return $pwresp;
    }

    public function resetPw($uid, $password_raw)
    {
        try {
            $resp = array();

            $password = PasswordHandler::encryptPw($password_raw);

            $expire = 1;
            $db = new DbConn;
            $tbl_members = $db->tbl_members;
            $tbl_tokens = $db->tbl_tokens;
            // prepare sql and bind parameters
            $stmt = $db->conn->prepare("UPDATE (".$tbl_members.",$tbl_tokens) LEFT JOIN $tbl_tokens AS t ON  (".$tbl_members.".id = t.userid)  SET ".$tbl_members.".password = :password, t.expired = :expire where ".$tbl_members.".id = :id");
            $stmt->bindParam(':id', $uid);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':expire', $expire);
            $stmt->execute();

            $resp['message'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Password Reset! <a href="'.AppConfig::pullSetting("base_url")."/login".'">Click here to sign in!</a></div><div id="returnVal" style="display:none;">true</div>';
            $resp['status'] = true;

            return $resp;
        } catch (PDOException $e) {
            $resp['message'] = 'Error: ' . $e->getMessage();
            $resp['status'] = false;

            return $resp;
        }
    }

    public static function validatePolicy($pw1, $pw2, $policy_enforce, $minlength)
    {
        try {
            $resp = array();

            if ($policy_enforce == true) {
                if ($pw1 == $pw2) {
                    if (strlen($pw1) >= $minlength && preg_match('/[A-Z]/', $pw1) > 0 && preg_match('/[a-z]/', $pw1) > 0) {

                        //Password policy success
                        $resp['status'] = true;
                        $resp['message'] = '';
                        return $resp;
                    } else {
                        $resp['status'] = false;
                        throw new Exception("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Password must include at least one upper and lower case letter and be at least {$minlength} characters long</div><div id='returnVal' style='display:none;'>false</div>");
                    }
                } else {
                    $resp['status'] = false;
                    throw new Exception("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Password fields must match</div><div id='returnVal' style='display:none;'>false</div>");
                }
            } else {
                if ($pw1 == $pw2) {
                    //No password policy success
                    $resp['status'] = true;
                    $resp['message'] = '';
                    return $resp;
                }
            }
        } catch (Exception $e) {
            //Password validation failed
            $resp['status'] = false;
            $resp['message'] = $e->getMessage();
            return $resp;
        }
    }
}
