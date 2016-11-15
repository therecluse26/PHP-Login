<?php
class Verify extends DbConn
{
    public function verifyUser($id, $verify)
    {
        try {
            $vdb = new DbConn;
            $tbl_members = $vdb->tbl_members;
            $verr = true;

            // prepare sql and bind parameters
            $vstmt = $vdb->conn->prepare("UPDATE ".$tbl_members." SET verified = :verify WHERE id in (:ids)");
            $vstmt->bindParam(':id', $id);
            $vstmt->bindParam(':verify', $verify);
            $vstmt->execute();


        } catch (PDOException $v) {

            $verr = 'Error: ' . $v->getMessage();

        }

    //Determines returned value ('true' or error code)
    $resp = $verr ? true : 'Failure';

        return $resp;

    }
}

