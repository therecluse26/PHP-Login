<?php
class UserData extends DbConn
{
    public static function userDataPull($ids, $admin)
    {        
        $idset = json_decode($ids);

        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

            try {

                $in = str_repeat('?,', count($idset) - 1) . '?';

                $sql = "SELECT id, email, username FROM ".$tbl_members." WHERE admin = ".$admin." and id IN ($in)"; 

                $stmt = $db->conn->prepare($sql);
                $stmt->execute($idset);

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {

                $result = "Error: " . $e->getMessage();

            }

        return $result;

    }
}
