<?php
class UserData extends DbConn
{
    public static function userDataPull($ids, $admin)
    {        
        $idset = json_decode($ids);
        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

        foreach ( $idset as $id ) {

            try {

                $sql = "SELECT id, email, username FROM ".$tbl_members." WHERE id = :id and admin = :admin";
                $stmt = $db->conn->prepare($sql);
                $stmt->bindParam(':admin', $admin);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                array_push($result, $stmt->fetchAll(PDO::FETCH_ASSOC));

            } catch (PDOException $e) {

                $result = "Error: " . $e->getMessage();

            }
        }

        return $result;

    }
}
