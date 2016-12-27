<?php
class Verify extends DbConn
{
    public static function verifyUser($userarr, $verify)
    {
        try {

            $idset = [];

            foreach($userarr as $user){
                array_push($idset, $user['id']);
            }

            if (count($idset) > 0) {
                $in  = str_repeat('?,', count($idset) - 1) . '?';

                $vdb = new DbConn;
                $tbl_members = $vdb->tbl_members;

                // prepare sql and bind parameters
                $vstmt = $vdb->conn->prepare("UPDATE ".$tbl_members." SET verified = ".$verify." WHERE id in ($in)");
                $vstmt->execute($idset);

                $vresp['status'] = true;
                $vresp['message'] = '';

            } else {

                $vresp['status'] = false;
                $vresp['message'] = 'User(s) not found';
            }



        } catch (PDOException $v) {

            $vresp['status'] = false;
            $vresp['message'] = 'Error: ' . $v->getMessage();
        }

        return $vresp;

    }
}

