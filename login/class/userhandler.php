<?php

class UserHandler extends DbConn
{
    public static function pullUserByEmail($email)
    {
        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

        try {
            $sql = "SELECT id, email, username FROM ".$tbl_members." WHERE email = :email LIMIT 1";
            $stmt = $db->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    public static function pullUserById($id)
    {
        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

        try {
            $sql = "SELECT id, email, username FROM ".$tbl_members." WHERE id = :id LIMIT 1";
            $stmt = $db->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }


    public static function getUserVerifyList()
    {
        try {
            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            $stmt = $db->conn->prepare("SELECT id, email, username, mod_timestamp as timestamp FROM ".$tbl_members."
                                        WHERE verified = 0 ORDER BY timestamp desc");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    /**
    * Returns list of all active users for DataTables consumption
    */
    public static function getActiveUsers($request, $columns)
    {
        $bindings = array();

        try {
            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            $where_sql = MiscFunctions::dt_filter($request, $columns, $bindings);
            $order_sql = MiscFunctions::dt_order($request, $columns);
            $limit_sql = MiscFunctions::dt_limit($request, $columns);

            if ($where_sql == '') {
                $where_sql = "WHERE";
            } else {
                $where_sql .= " AND";
            }

            $stmt = $db->conn->prepare("SELECT m.mod_timestamp as timestamp, m.id, m.email, m.username, GROUP_CONCAT(r.name) roles FROM ".$tbl_members." m
                                        INNER JOIN member_roles mr on mr.member_id = m.id
                                        INNER JOIN roles r on mr.role_id = r.id
                                        $where_sql
                                        r.name NOT IN ('Superadmin')
                                        AND m.verified = 1
                                        AND m.banned = 0
                                        GROUP BY m.id
                                        $order_sql
                                        $limit_sql");

            $records_total = $db->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
                                        INNER JOIN member_roles mr on mr.member_id = m.id
                                        INNER JOIN roles r on mr.role_id = r.id
                                        WHERE r.name NOT IN ('Superadmin')
                                        AND m.verified = 1
                                        AND m.banned = 0");

            $records_filtered = $db->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
                                        INNER JOIN member_roles mr on mr.member_id = m.id
                                        INNER JOIN roles r on mr.role_id = r.id
                                        $where_sql
                                        r.name NOT IN ('Superadmin')
                                        AND m.verified = 1
                                        AND m.banned = 0");

            // Bind parameters
            if (is_array($bindings)) {
                for ($i=0, $ien=count($bindings) ; $i<$ien ; $i++) {
                    $binding = $bindings[$i];
                    $stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
                    $records_filtered->bindValue($binding['key'], $binding['val'], $binding['type']);
                }
            }

            $records_total->execute();
            $records_filtered->execute();
            $stmt->execute();

            $active_user_count = $records_total->fetchColumn();
            $filtered_user_count = $records_filtered->fetchColumn();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $return_data = array(
              "draw"            => $request['draw'],
              "recordsTotal"    => $active_user_count,
              "recordsFiltered" => $filtered_user_count,
              "data" => MiscFunctions::data_output($columns, $data)
            );

            $result = json_encode($return_data);

            return $return_data;
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
            return $result;
        }
    }

    /**
    * Notifies admins of new user signup
    */
    public static function adminEmailList()
    {
        try {
            $db = new DbConn;
            $stmt = $db->conn->prepare("SELECT email FROM ".$db->tbl_members." m
                                        INNER JOIN member_roles mr on mr.member_id = m.id
                                        INNER JOIN roles r on mr.role_id = r.id
                                        WHERE r.name in ('Admin', 'Superadmin')");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    /**
    * Deletes user by `$userid`
    **/
    public static function deleteUser($userid)
    {
        try {
            $ddb = new DbConn;
            $tbl_members = $ddb->tbl_members;
            $derr = '';

            $dstmt = $ddb->conn->prepare('delete from '.$tbl_members.' WHERE id = :uid');
            $dstmt->bindParam(':uid', $userid);
            $dstmt->execute();
        } catch (PDOException $d) {
            $derr = 'Error: ' . $d->getMessage();
        }

        $resp = ($derr == '') ? true : $derr;

        return $resp;
    }

    /**
    * Bans user by `$userid`
    **/
    public static function banUser($user_id, $ban_hours = 0, $reason = null)
    {
        try {
            $db = new DbConn;
            $err = null;
            //$curr_timestamp = date("Y-m-d H:i:s");

            $stmt = $db->conn->prepare('
              UPDATE '.$db->tbl_members.' SET banned = 1 WHERE id = :user_id;
              INSERT INTO '.$db->tbl_member_jail.' (user_id, banned_hours, reason) VALUES (:user_id, :banned_hours, :reason);
            ');

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':banned_hours', $ban_hours);
            $stmt->bindParam(':reason', $reason);
            //$stmt->bindParam(':timestamp', $curr_timestamp);
            $stmt->execute();
        } catch (PDOException $e) {
            $err = 'Error: ' . $e->getMessage();
        }

        $resp = ($err == null) ? true : $err;

        return $resp;
    }
}
