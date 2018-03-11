<?php
class PasswordForm extends DbConn
{
    public function resetPw ($uid, $password_raw)
    {
        try {
            $resp = array();

            $password = PasswordCrypt::encryptPw($password_raw);

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
}
