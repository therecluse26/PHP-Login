<?php
class AdminUserPull extends DbConn
{
    public static function UserList()
    {
        try {
            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            $stmt = $db->conn->prepare("SELECT id, email, username FROM ".$tbl_members." WHERE verified = 0 and admin = 0");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            $result = "Error: " . $e->getMessage();

        }

        return $result;

    }
}
