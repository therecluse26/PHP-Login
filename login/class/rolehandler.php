<?php
/**
* Handles role functionality
**/
class RoleHandler extends DbConn
{
    /*
    * Checks if user has specified role by name
    */
    public function checkRole($user_id, $role_name): bool
    {
        try {
            $sql = "SELECT mr.id FROM ".$this->tbl_member_roles." mr
                      INNER JOIN roles r on mr.role_id = r.id
                      WHERE mr.member_id = :member_id
                      AND r.name = :role_name LIMIT 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':member_id', $user_id);
            $stmt->bindParam(':role_name', $role_name);
            $stmt->execute();
            $result = $stmt->fetchColumn();

            if ($result) {
                $return = true;
            } else {
                $return = false;
            }
        } catch (PDOException $e) {
            $return = false;
        }

        return $return;
    }

    /*
    * Returns the default role for new user creation
    */
    public static function getDefaultRole(): int
    {
        $db = new DbConn;

        try {
            $sql = "SELECT id FROM ".$db->tbl_roles." WHERE default_role = 1";

            $stmt = $db->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchColumn();

            $return = $result;
        } catch (PDOException $e) {
            $return = false;
        }

        unset($db);

        return $return;
    }

    /*
    * Returns all roles
    */
    public function listAllRoles(): array
    {
        try {
            $sql = "SELECT DISTINCT id, name, description, default_role FROM ".$this->tbl_roles;

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_NUM);

            return $result;
        } catch (PDOException $e) {
            $return = false;
        }

        return $return;
    }

    /*
    * Returns all users of a given $role_id
    */
    public function listRoleUsers($role_id): array
    {
        try {
            $sql = "SELECT m.id, m.username FROM ".$this->tbl_member_roles." mr
                    INNER JOIN roles r on mr.role_id = r.id
                    INNER JOIN members m on mr.member_id = m.id
                    WHERE r.id = :role_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':role_id', $role_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_NUM);

            return $result;
        } catch (PDOException $e) {
            $return = false;
        }

        return $return;
    }

    /*
    * Returns all roles of a given $user_id
    */
    public function listUserRoles($user_id): array
    {
        try {
            $sql = "SELECT r.id, r.name, r.description FROM ".$this->tbl_member_roles." mr
                  INNER JOIN roles r on mr.role_id = r.id
                  INNER JOIN members m on mr.member_id = m.id
                  WHERE m.id = :member_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':member_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_NUM);

            return $result;
        } catch (PDOException $e) {
            $return = false;
        }

        return $return;
    }

    public function createRole($role_name, $role_desc, $default = false): bool
    {
    }

    public function updateRole($role_id, $role_name = null, $role_desc = null, $default = null): bool
    {
    }

    public function deleteRole($role_id): bool
    {
    }

    public function assignRole($role_id, $user_id): bool
    {
    }

    public function unassignRole($role_id, $user_id): bool
    {
    }
}
