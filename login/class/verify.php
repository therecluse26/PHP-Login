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
            
            $in  = str_repeat('?,', count($idset) - 1) . '?';
            
            $vdb = new DbConn;
            $tbl_members = $vdb->tbl_members;

            // prepare sql and bind parameters
            $vstmt = $vdb->conn->prepare("UPDATE ".$tbl_members." SET verified = ".$verify." WHERE id in ($in)");
            $vstmt->execute($idset);

            $vresp = 'true';


        } catch (PDOException $v) {

            $vresp = 'Error: ' . $v->getMessage();

        }

    //Determines returned value ('true' or error code)
    //$resp = ($vresp == true) ? true : 'Failure';
    if($vresp == 'true'){
        $resp = true;
    } else {
        $resp = false;
    }
        return $resp;

    }
}

