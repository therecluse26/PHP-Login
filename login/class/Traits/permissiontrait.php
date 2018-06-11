<?php
/**
* PHPLogin\Traits\PermissionTrait
*/
namespace PHPLogin\Traits;

/**
 * Re-usable permission functionality
 */
trait PermissionTrait
{
    /**
    * Checks if user has specified permission by name
    *
    * @param string $user_id User ID
    * @param string $permission_name Permission Name
    *
    * @return boolean
    */
    public function checkPermission($user_id, $permission_name): bool
    {
        try {
            $sql = "SELECT IFNULL((SELECT CASE WHEN rp.id IS NOT NULL THEN 1 END FROM ".$this->tbl_role_permissions." rp
                      INNER JOIN ".$this->tbl_roles." r on rp.role_id = r.id
                      INNER JOIN ".$this->tbl_permissions." p on rp.permission_id = p.id
                      INNER JOIN ".$this->tbl_member_roles." mr on mr.role_id = r.id
                    WHERE mr.member_id = :member_id
                      AND p.name = :permission_name LIMIT 1) , 0) as has_permission";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':member_id', $user_id);
            $stmt->bindParam(':permission_name', $permission_name);
            $stmt->execute();
            $result = $stmt->fetchColumn();

            if ($result) {
                $return = true;
            } else {
                $return = false;
            }
        } catch (\PDOException $e) {
            $return = false;
        }

        return $return;
    }
}
