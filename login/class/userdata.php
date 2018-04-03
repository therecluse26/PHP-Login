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

            if ($admin == 0) {
                $sql = "SELECT m.id, m.email, m.username FROM ".$tbl_members." m
                        INNER JOIN member_roles mr on mr.member_id = m.id
                        INNER JOIN roles r on mr.role_id = r.id
                        WHERE r.name NOT IN ('Admin', 'Superadmin') and m.id IN ($in)";
            } elseif ($admin == 1) {
                $sql = "SELECT m.id, m.email, m.username FROM ".$tbl_members." m
                        INNER JOIN member_roles mr on mr.member_id = m.id
                        INNER JOIN roles r on mr.role_id = r.id
                        WHERE r.name IN ('Admin', 'Superadmin') and m.id IN ($in)";
            }

            $stmt = $db->conn->prepare($sql);
            $stmt->execute($idset);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    public static function pullUserPassword($id)
    {
        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

        try {
            $sql = "SELECT password FROM ".$tbl_members." WHERE id = :id LIMIT 1";
            $stmt = $db->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    public static function upsertAccountInfo($uid, $dataarray)
    {
        unset($dataarray['id']);

        //Remove potentially hacked array values
        if (!empty($dataarray)) {
            unset($dataarray['verified']);
            unset($dataarray['mod_timestamp']);
            unset($dataarray['username']);

            $datafields = implode(', ', array_keys($dataarray));

            $insdata = implode('\', \'', $dataarray);

            foreach ($dataarray as $key => $value) {
                if (isset($updata)) {
                    $updata = $updata.$key.' = \''.$value.'\', ';
                } else {
                    $updata = $key.' = \''.$value.'\', ';
                }
            }

            $updata = rtrim($updata, ", ");

            //Upsert user data
            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            // prepare sql and bind parameters
            $stmt = $db->conn->prepare("INSERT INTO ".$tbl_members." (id, $datafields) values ('$uid', '$insdata') ON DUPLICATE KEY UPDATE $updata");

            $status = $stmt->execute();

            return $status;
        } else {
            return false;
        }
    }
}
