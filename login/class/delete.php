<?php
/**
* Deletes user from `members` table.
*
* Trigger automatically inserts deleted user into `deleted_members` table and saves them for 30 days until a database event removes them.
* Erasing from `deleted_members` table will result in permanent deletion
**/
class Delete extends DbConn
{
    /**
    * Deletes user by `$userid`
    **/
    public static function deleteUser($userid)
    {
        try {

            $ddb = new DbConn;
            $tbl_members = $ddb->tbl_members;
            $derr = '';

            $ddb = new DbConn;
            $dstmt = $ddb->conn->prepare('delete from '.$tbl_members.' WHERE id = :uid');
            $dstmt->bindParam(':uid', $userid);
            $dstmt->execute();

        } catch (PDOException $d) {

            $derr = 'Error: ' . $d->getMessage();
        }

    $resp = ($derr == '') ? true : $derr;

        return $resp;
    }

    public static function deleteLog($logid)
    {
        try {

            $ddb = new DbConn;
            $tbl_mailLog = $ddb->tbl_mailLog;
            $derr = array();

            $dstmt = $ddb->conn->prepare('update '.$tbl_mailLog.' set isread = 1 WHERE id = :logid');
            $dstmt->bindParam(':logid', $logid);
            $dstmt->execute();
            $derr['status'] = 1;
            $derr['message'] = $dstmt->rowCount();

        } catch (PDOException $d) {

            $derr['status'] = 0;
            $derr['message'] = 0;
            trigger_error('Error: ' . $d->getMessage());
        }

    $resp = ($derr == '') ? true : $derr;

        return $resp;
    }
}
