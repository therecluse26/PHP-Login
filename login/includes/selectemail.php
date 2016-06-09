<?php
class SelectEmail extends DbConn
{
    public function emailPull($id)
    {
        try {
            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            $stmt = $db->conn->prepare("SELECT email, username FROM ".$tbl_members." WHERE id = :myid");
            $stmt->bindParam(':myid', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            $result = "Error: " . $e->getMessage();

        }

        //Queries database with prepared statement
        return $result;

    }
}
