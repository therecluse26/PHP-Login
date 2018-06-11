<?php
/**
 * PHPLogin\UserData extends DbConn
 */
namespace PHPLogin;

/**
* User data handler
*
* Manages non-profile related user data such as email, username, password, etc
*/
class UserData extends DbConn
{
    /**
     * Pulls user id, email, username and role(s)
     *
     * @param  string $ids    JSON string of user IDs
     * @param  bool   $admin  Is Admin
     *
     * @return array
     */
    public static function userDataPull(string $ids, bool $admin)
    {
        $idset = json_decode($ids);

        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $tbl_member_roles = $db->tbl_member_roles;
        $tbl_roles = $db->tbl_roles;
        $result = array();

        try {
            $in = str_repeat('?,', count($idset) - 1) . '?';

            $resp = array(['id'=>null, 'email'=>null, 'username'=>null]);

            $sql = "SELECT DISTINCT m.id, m.email, m.username, r.name as role FROM ".$tbl_members." m
                    LEFT JOIN ".$tbl_member_roles." mr on mr.member_id = m.id
                    LEFT JOIN ".$tbl_roles." r on mr.role_id = r.id
                    WHERE m.id IN ($in)";

            $stmt = $db->conn->prepare($sql);
            $stmt->execute($idset);

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $is_admin = (array_search('Admin', $result) || array_search('Superadmin', $result));
            if ($admin == 0) {
                if (!$is_admin) {
                    $resp = array([ 'id'=>$result[0]['id'], 'email'=>$result[0]['email'], 'username'=>$result[0]['id'] ]);
                }
            } elseif ($admin == 1) {
                if ($is_admin) {
                    $resp = array([ 'id'=>$result[0]['id'], 'email'=>$result[0]['email'], 'username'=>$result[0]['id'] ]);
                }
            }
        } catch (\PDOException $e) {
            $resp = "Error: " . $e->getMessage();
        }

        return $resp;
    }

    /**
     * Returns array of users from list of IDs
     *
     * @param  string $ids JSON list of User IDs
     *
     * @return array
     */
    public function listSelectedUsers(string $ids)
    {
        $idset = json_decode($ids);
        $result = array();

        try {
            $in = str_repeat('?,', count($idset) - 1) . '?';

            $sql = "SELECT DISTINCT m.id FROM ".$this->tbl_members." m
                INNER JOIN ".$this->tbl_member_roles." mr on mr.member_id = m.id
                INNER JOIN ".$this->tbl_roles." r on mr.role_id = r.id AND r.name != 'Superadmin'
                WHERE m.id IN ($in)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($idset);

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Returns hashed user password
     *
     * @param  string $id User ID
     *
     * @return array
     */
    public static function pullUserPassword(string $id)
    {
        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

        try {
            $sql = "SELECT password FROM ".$tbl_members." WHERE id = :id LIMIT 1";
            $stmt = $db->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Update or insert user account data
     *
     * @param  string $uid       User ID
     * @param  array  $dataarray Array of data to update/insert
     *
     * @return mixed
     */
    public static function upsertAccountInfo(string $uid, array $dataarray)
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
