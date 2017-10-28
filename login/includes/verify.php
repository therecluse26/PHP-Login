<?php
class Verify extends DbConn
{
    public function verifyUser($uid, $hash)
    {
        try {
            $vdb = new DbConn;
            $tbl_members = $vdb->tbl_members;
            $verr = '';

            $vstmt = $vdb->conn->prepare('SELECT mod_timestamp from '.$tbl_members.' WHERE id = :uid');
            $vstmt->bindParam(':uid', $uid);
            $vstmt->execute();
            $result = $vstmt.fetch(PDO::FETCH_ASSOC);
            
            // Check if uid exist
            if (!isset($result['mod_timestamp'])) {
                return 'User does not exist';
            }

            // Verification can only promote once
            if (hash('md5', $result['mod_timestamp']) !== $hash) {
                return 'This link has expired';
            }

            // prepare sql and bind parameters
            $vstmt = $vdb->conn->prepare('UPDATE '.$tbl_members.' SET verified = :verify WHERE id = :uid');
            $vstmt->bindParam(':uid', $uid);
            $vstmt->bindParam(':verify', $verify);
            $vstmt->execute();

        } catch (PDOException $v) {

            $verr = 'Error: ' . $v->getMessage();

        }

        //Determines returned value ('true' or error code)
        $resp = ($verr == '') ? 'true' : $verr;

        return $resp;

    }
}
