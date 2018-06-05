<?php
namespace PHPLogin;

/**
* Handles permission functionality
**/
class PermissionHandler extends DbConn
{
    use Traits\PermissionTrait;
    /*
    * Returns all permissions
    */
    public function listAllPermissions(): array
    {
        try {
            $sql = "SELECT DISTINCT id, name, description, category, required
                    FROM ".$this->tbl_permissions;

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            $return = false;
        }

        return $return;
    }

    /*
    * Returns data of given permission
    */
    public function getPermissionData($permission_id): array
    {
        try {
            $sql = "SELECT DISTINCT id, name, description, category, required
                  FROM ".$this->tbl_permissions. " WHERE id = :permission_id LIMIT 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permission_id', $permission_id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            $return = false;
        }

        return $return;
    }

    /*
    * Returns all roles of a given $permission_id
    */
    public function listPermissionRoles($permission_id): array
    {
        try {
            $sql = "SELECT r.id, r.name FROM ".$this->tbl_role_permissions." rp
                    INNER JOIN ".$this->tbl_roles." r on rp.role_id = r.id
                    WHERE rp.permission_id = :permission_id ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permission_id', $permission_id);

            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            http_response_code(500);
            $return = ["Error" => $e->getMessage()];
            return $return;
        }
    }

    /*
    * Returns all roles
    */
    public function listAllRoles(): array
    {
        try {
            $sql = "SELECT DISTINCT id, name
                    FROM ".$this->tbl_roles;

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            $return = false;
        }

        return $return;
    }

    /*
    * Updates all roles of a given $permission_id
    */
    public function updatePermissionRoles($roles, $permission_id): bool
    {
        try {
            $this->conn->beginTransaction();

            $sqldel = "DELETE FROM {$this->tbl_role_permissions} where permission_id = :permission_id";

            $stmtdel = $this->conn->prepare($sqldel);
            $stmtdel->bindParam(':permission_id', $permission_id);
            $stmtdel->execute();

            if (!empty($roles)) {
                $chunks = MiscFunctions::placeholders($roles, ",", $permission_id);

                $sql = "REPLACE INTO {$this->tbl_role_permissions}
                          (role_id, permission_id)
                          VALUES $chunks";

                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
            }


            $this->conn->commit();

            return true;
        } catch (\PDOException $e) {
            $this->conn->rollback();
            error_log($e->getMessage());
            $return = false;
        }

        return $return;
    }



    public function createPermission($permission_name, $permission_desc, $permission_cat = 'General'): bool
    {
        try {
            $sql = "INSERT INTO ".$this->tbl_permissions."
                          (name, description, category) values (:permission_name, :permission_desc, :permission_cat)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permission_name', $permission_name);
            $stmt->bindParam(':permission_desc', $permission_desc);
            $stmt->bindParam(':permission_cat', $permission_cat);
            $stmt->execute();

            $return = true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $return = false;
        }

        return $return;
    }

    public function updatePermission($permission_id, $permission_name = null, $permission_desc = null, $permission_cat = null): bool
    {
        try {
            $sql = "UPDATE ".$this->tbl_permissions." SET
                      name = :permission_name,
                      description = :permission_desc,
                      category = :permission_cat
                    where id = :permission_id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':permission_id', $permission_id);
            $stmt->bindParam(':permission_name', $permission_name);
            $stmt->bindParam(':permission_desc', $permission_desc);
            $stmt->bindParam(':permission_cat', $permission_cat);
            $stmt->execute();

            $return = true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $return = false;
        }

        return $return;
    }

    public function deletePermission($permission_id): bool
    {
        try {
            $sql = "DELETE FROM ".$this->tbl_permissions." where id = :permission_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permission_id', $permission_id);
            $stmt->execute();

            $return = true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $return = false;
        }

        return $return;
    }

    public function assignPermissionToRole($permission_id, $role_id): bool
    {
        try {
            $sql = "REPLACE INTO ".$this->tbl_role_permissions."
                    (permission_id, role_id) values (:permission_id, :role_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permission_id', $permission_id);
            $stmt->bindParam(':role_id', $role_id);
            $stmt->execute();

            $return = true;
        } catch (\PDOException $e) {
            $return = false;
        }

        return $return;
    }

    public function checkPermissionRequired($id)
    {
        try {
            $sql = "SELECT DISTINCT required
                  FROM ".$this->tbl_permissions." where id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchColumn();

            return $result;
        } catch (\PDOException $e) {
            $return = false;
        }

        return $return;
    }
}
