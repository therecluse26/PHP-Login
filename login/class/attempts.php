<?php
class Attempts extends DbConn
{
    public static function checkAtt($username)
    {
        try {

            $db = new DbConn;
            $tbl_attempts = $db->tbl_attempts;
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $err = '';

            $sql = "SELECT Attempts as attempts, lastlogin FROM ".$tbl_attempts." WHERE IP = :ip and Username = :username";

            $stmt = $db->conn->prepare($sql);
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
        $resp = ($err == '') ? 'true' : $err;

        return $resp;

    }
}
