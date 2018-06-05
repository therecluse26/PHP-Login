<?php
namespace PHPLogin;

class AdminFunctions
{
    /**
    *  Pulls user list for verification or deletion
    */
    public static function getUserVerifyList()
    {
        try {
            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            $stmt = $db->conn->prepare("SELECT id, email, username, mod_timestamp as timestamp FROM ".$tbl_members."
                                      WHERE verified = 0 ORDER BY timestamp desc");
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    /**
    * Returns list of all active users for DataTables consumption
    */
    public static function getActiveUsers($request, $columns): array
    {
        $bindings = array();

        try {
            $db = new DbConn;
            $tbl_members = $db->tbl_members;
            $tbl_member_roles = $db->tbl_member_roles;
            $tbl_roles = $db->tbl_roles;

            $where_sql = MiscFunctions::dt_filter($request, $columns, $bindings);
            $order_sql = MiscFunctions::dt_order($request, $columns);
            $limit_sql = MiscFunctions::dt_limit($request, $columns);

            if ($where_sql == '') {
                $where_sql = "WHERE";
            } else {
                $where_sql .= " AND";
            }

            $stmt = $db->conn->prepare("SELECT m.mod_timestamp as timestamp, m.id, m.email, m.username, GROUP_CONCAT(r.name) roles
                                        FROM ".$tbl_members." m
                                        INNER JOIN ".$tbl_member_roles." mr on mr.member_id = m.id
                                        INNER JOIN ".$tbl_roles." r on mr.role_id = r.id
                                        $where_sql
                                        m.verified = 1
                                        AND m.banned = 0
                                        GROUP BY m.id
                                        $order_sql
                                        $limit_sql");

            $records_total = $db->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
                                      WHERE m.verified = 1
                                      AND m.banned = 0");

            $records_filtered = $db->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
                                      $where_sql
                                      m.verified = 1
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

            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $return_data = array(
              "draw"            => $request['draw'],
              "recordsTotal"    => $active_user_count,
              "recordsFiltered" => $filtered_user_count,
              "data" => MiscFunctions::data_output($columns, $data)
            );

            return $return_data;
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
            return $result;
        }
    }

    /**
    * Returns list of all active users for DataTables consumption
    */
    public static function getAllRoles($request, $columns): array
    {
        $bindings = array();

        try {
            $db = new DbConn;

            $sql = "SELECT r.id, r.name, r.description, ifnull(count(mr.id), 0) as user_count, NULL as users,
                    CASE WHEN
                      r.required = 0 THEN concat('<button id=\'editrole_', r.id, '\' onclick=\'editRole(',r.id,')\' class=\'btn btn-warning\'>Edit</button>')
                      ELSE null END
                    AS edit
                    FROM ".$db->tbl_roles." r
                  	LEFT JOIN ".$db->tbl_member_roles." mr on r.id = mr.role_id
                    WHERE r.name != 'Superadmin'
                    GROUP BY r.id, r.name, r.description";

            $stmt = $db->conn->prepare($sql);

            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $return_data = array(
              "data" => MiscFunctions::data_output($columns, $data)
            );

            return $return_data;
        } catch (\PDOException $e) {
            http_response_code(500);
            $result = ["Error" => $e->getMessage()];
            return $result;
        }
    }

    /**
    * Returns list of all active users for DataTables consumption
    */
    public static function getAllPermissions($request, $columns): array
    {
        $bindings = array();

        try {
            $db = new DbConn;

            $sql = "SELECT p.id, p.name, p.description, p.category, NULL AS roles,
                    CASE WHEN
                      p.required = 0 THEN concat('<button id=\'editpermission_', p.id, '\' onclick=\'editPermission(',p.id,')\' class=\'btn btn-warning\'>Edit</button>')
                      ELSE null END
                    AS edit
                    FROM ".$db->tbl_permissions." p";

            $stmt = $db->conn->prepare($sql);

            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $return_data = array(
              "data" => MiscFunctions::data_output($columns, $data)
            );

            return $return_data;
        } catch (\PDOException $e) {
            http_response_code(500);
            $result = ["Error" => $e->getMessage()];
            return $result;
        }
    }

    /**
    * Returns list of all unverified users for DataTables consumption
    */
    public static function getUnverifiedUsers($request, $columns)
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

            $stmt = $db->conn->prepare("SELECT m.mod_timestamp as timestamp, m.id, m.email, m.username FROM ".$tbl_members." m
                                      $where_sql m.verified = 0
                                      AND m.banned = 0
                                      GROUP BY m.id
                                      $order_sql
                                      $limit_sql");

            $records_total = $db->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
                                      WHERE m.verified = 0
                                      AND m.banned = 0");

            $records_filtered = $db->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
                                      $where_sql m.verified = 0
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

            $unverified_user_count = $records_total->fetchColumn();
            $filtered_user_count = $records_filtered->fetchColumn();

            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $return_data = array(
            "draw"            => $request['draw'],
            "recordsTotal"    => $unverified_user_count,
            "recordsFiltered" => $filtered_user_count,
            "data" => MiscFunctions::data_output($columns, $data)
          );

            $result = json_encode($return_data);

            return $return_data;
        } catch (\PDOException $e) {
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
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }
}
