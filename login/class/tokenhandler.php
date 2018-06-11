<?php
/**
 * PHPLogin\TokenHandler
 */
namespace PHPLogin;

/**
* Token handling class
*
* Handles token storage and retrieval in database
*/
class TokenHandler
{
    /**
     * Pulls token from database
     *
     * @param  string $tokenid Token ID
     * @param  string $userid  User ID
     * @param  bool   $expire  Token expired
     *
     * @return array
     */
    public static function selectToken(string $tokenid, string $userid, bool $expire)
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
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            unset($db);

            return $result;
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
            return $result;
        }
    }

    /**
     * Inserts or updates token in database
     *
     * @param  string $userid  User ID
     * @param  string $tokenid Token ID
     * @param  bool   $expire  Token expired
     *
     * @return bool
     */
    public static function replaceToken(string $userid, string $tokenid, bool $expire)
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
