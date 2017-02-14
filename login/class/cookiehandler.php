<?php
/**
* Contains all cookie handling methods
**/
class CookieHandler
{
    /**
    * Generates a cookie
    * `$cookieid` = ID of cookie in `cookies` table.
    * `$userid` = ID of user generating the cookie.
    * `$tokenid` = ID of the JWT token.
    * `$token` = JWT token itself.
    * `$exptime` = Expiration time of cookie (in seconds)
    **/
    public static function generateCookie($cookieid, $userid, $tokenid, $token, $exptime)
    {

        try {
            setcookie("usertoken", $token, time() + $exptime, "/", "", false, true);

            $db = new DbConn;
            $stmt = $db->conn->prepare("REPLACE INTO $db->tbl_cookies (cookieid, userid, tokenid, expired) values(:cookieid, :userid, :tokenid, 0)");
            $stmt->bindParam(':cookieid', $cookieid);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':tokenid', $tokenid);
            $stmt->execute();

            return true;

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
    * Checks if cookie is set
    **/
    public static function decodeCookie($usertoken)
    {
        $cookie = array();

        try {

            if (!isset($_COOKIE[$usertoken])) {

                $cookie['status'] = false;
                return $cookie;

            } else {
                $cookie['status'] = true;
                $cookie['value'] = $_COOKIE[$usertoken];
                return $cookie;
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
    /**
    * Validates cookie against `cookies` table.
    * `$userid` = ID of user that generated the cookie.
    * `$cookieid` = Cookie ID in both database and cookie data.
    * `$tokenid` = Token ID in both database and cookie data.
    **/
    public static function validateCookie($userid, $cookieid, $tokenid)
    {
        try {
            $db = new DbConn;
            $stmt = $db->conn->prepare("SELECT cookieid, userid, tokenid, expired FROM $db->tbl_cookies WHERE userid = :userid and tokenid = :tokenid and cookieid = :cookieid and expired = 0");
            $stmt->bindParam(':cookieid', $cookieid);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':tokenid', $tokenid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
