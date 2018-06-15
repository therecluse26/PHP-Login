<?php
/**
 * PHPLogin\AdminFunctions extends AppConfig
 */
namespace PHPLogin;

/**
 * Admin-specific functions
 *
 * Various methods specifically related to pages within the /admin subdirectory
 */
class AdminFunctions extends AppConfig
{
    /**
    *  Pulls user list for verification or deletion
    *
    * @return array Array of unverified users
    */
    public function getUserVerifyList(): array
    {
        try {
            $tbl_members = $this->tbl_members;

            $stmt = $this->conn->prepare("SELECT id, email, username, mod_timestamp as timestamp FROM ".$tbl_members."
                                      WHERE verified = 0 ORDER BY timestamp desc");
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result['status'] = false;
            $result['message'] = "Error: " . $e->getMessage();
        }

        return $result;
    }

    /**
    * Returns list of all active users for DataTables consumption
    *
    * @param array $request $_GET request from ajax call made by DataTables
    * @param array $columns Array of columns to return
    *
    * @return array Array of active, verified users
    */
    public function getActiveUsers($request, $columns): array
    {
        $bindings = array();

        try {
            $where_sql = MiscFunctions::dt_filter($request, $columns, $bindings);
            $order_sql = MiscFunctions::dt_order($request, $columns);
            $limit_sql = MiscFunctions::dt_limit($request, $columns);

            if ($where_sql == '') {
                $where_sql = "WHERE";
            } else {
                $where_sql .= " AND";
            }

            $stmt = $this->conn->prepare("SELECT m.mod_timestamp as timestamp, m.id, m.email, m.username, GROUP_CONCAT(r.name) roles
                                        FROM ".$this->tbl_members." m
                                        INNER JOIN ".$this->tbl_member_roles." mr on mr.member_id = m.id
                                        INNER JOIN ".$this->tbl_roles." r on mr.role_id = r.id
                                        $where_sql
                                        m.verified = 1
                                        AND m.banned = 0
                                        GROUP BY m.id
                                        $order_sql
                                        $limit_sql");

            $records_total = $this->conn->prepare("SELECT count(m.id) as users FROM ".$this->tbl_members." m
                                      WHERE m.verified = 1
                                      AND m.banned = 0");

            $records_filtered = $this->conn->prepare("SELECT count(m.id) as users FROM ".$this->tbl_members." m
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
            $result['status'] = false;
            $result['message'] = "Error: " . $e->getMessage();
            return $result;
        }
    }

    /**
    * Returns list of all active users for DataTables consumption
    *
    * @param array $request $_GET request from ajax call made by DataTables
    * @param array $columns Array of columns to return
    *
    * @return array Array of all roles
    */
    public function getAllRoles($request, $columns): array
    {
        $bindings = array();

        try {
            $sql = "SELECT r.id, r.name, r.description, ifnull(count(mr.id), 0) as user_count, NULL as users,
                    CASE WHEN
                      r.required = 0 THEN concat('<button id=\'editrole_', r.id, '\' onclick=\'editRole(',r.id,')\' class=\'btn btn-warning\'>Edit</button>')
                      ELSE null END
                    AS edit
                    FROM ".$this->tbl_roles." r
                  	LEFT JOIN ".$this->tbl_member_roles." mr on r.id = mr.role_id
                    WHERE r.name != 'Superadmin'
                    GROUP BY r.id, r.name, r.description";

            $stmt = $this->conn->prepare($sql);

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
    *
    * @param array $request $_GET request from ajax call made by DataTables
    * @param array $columns Array of columns to return
    *
    * @return array Array of all permissions
    */
    public function getAllPermissions($request, $columns): array
    {
        $bindings = array();

        try {
            $sql = "SELECT p.id, p.name, p.description, p.category, NULL AS roles,
                    CASE WHEN
                      p.required = 0 THEN concat('<button id=\'editpermission_', p.id, '\' onclick=\'editPermission(',p.id,')\' class=\'btn btn-warning\'>Edit</button>')
                      ELSE null END
                    AS edit
                    FROM ".$this->tbl_permissions." p";

            $stmt = $this->conn->prepare($sql);

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
    *
    * @param array $request $_GET request from ajax call made by DataTables
    * @param array $columns Array of columns to return
    *
    * @return array Array of unverified users
    */
    public function getUnverifiedUsers($request, $columns): array
    {
        $bindings = array();

        try {
            $tbl_members = $this->tbl_members;

            $where_sql = MiscFunctions::dt_filter($request, $columns, $bindings);
            $order_sql = MiscFunctions::dt_order($request, $columns);
            $limit_sql = MiscFunctions::dt_limit($request, $columns);

            if ($where_sql == '') {
                $where_sql = "WHERE";
            } else {
                $where_sql .= " AND";
            }

            $stmt = $this->conn->prepare("SELECT m.mod_timestamp as timestamp, m.id, m.email, m.username FROM ".$tbl_members." m
                                      $where_sql m.verified = 0
                                      AND m.banned = 0
                                      GROUP BY m.id
                                      $order_sql
                                      $limit_sql");

            $records_total = $this->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
                                      WHERE m.verified = 0
                                      AND m.banned = 0");

            $records_filtered = $this->conn->prepare("SELECT count(m.id) as users FROM ".$tbl_members." m
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
    *
    * @return array Reruns all email addresses of all admins
    */
    public function adminEmailList(): array
    {
        try {
            $sql = "SELECT email FROM ".$this->tbl_members." m
                      INNER JOIN ".$this->tbl_member_roles." mr on mr.member_id = m.id
                      INNER JOIN ".$this->tbl_roles." r on mr.role_id = r.id
                      WHERE r.name in ('Admin', 'Superadmin')";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result['status'] = false;
            $result['message'] = "Error: " . $e->getMessage();
        }

        return $result;
    }
}
