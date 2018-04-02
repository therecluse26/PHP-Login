<?php
class TokenHandler extends DbConn
{
    public static function selectToken($tokenid, $userid, $expire)
    {
        $hrsvalid="-".AppConfig::pullSetting('token_validity')." hours";

        $valid =  date('Y-m-d H:i:s', strtotime($hrsvalid));

        try {
            $db = new DbConn;

            $stmt = $db->conn->prepare("SELECT tokenid FROM $db->tbl_tokens WHERE tokenid = :tokenid AND userid = :userid AND expired = :expire AND timestamp >= :valid");
            $stmt->bindParam(':tokenid', $tokenid);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':expire', $expire);
            $stmt->bindParam(':valid', $valid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
            return $result;
        }
    }

    public static function replaceToken($userid, $tokenid, $expire)
    {
        try {
            $db = new DbConn;

            $stmt = $db->conn->prepare("REPLACE INTO $db->tbl_tokens (tokenid, userid, expired) values(:tokenid, :userid, 0)");
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':tokenid', $tokenid);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
