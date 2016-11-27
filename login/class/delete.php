<?php
class Delete extends DbConn
{
    public static function deleteUser($uid)
    {
        try {
            
            $ddb = new DbConn;
            $tbl_members = $ddb->tbl_members;
            $derr = '';

            $ddb = new DbConn;
            $dstmt = $ddb->conn->prepare('delete from '.$tbl_members.' WHERE id = :uid');
            $dstmt->bindParam(':uid', $uid);
            $dstmt->execute();

        } catch (PDOException $d) {

            $derr = 'Error: ' . $d->getMessage();

        }

    //Determines returned value ('true' or error code)
    $resp = ($derr == '') ? true : $derr;

        return $resp;

    }
}
