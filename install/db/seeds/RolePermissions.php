<?php


use Phinx\Seed\AbstractSeed;

class RolePermissions extends AbstractSeed
{
    public function run()
    {
        $data = [
                  ['id' => 1, 'role_id' => 1, 'permission_id' => 1],
                  ['id' => 2, 'role_id' => 1, 'permission_id' => 2],
                  ['id' => 3, 'role_id' => 1, 'permission_id' => 3],
                  ['id' => 4, 'role_id' => 1, 'permission_id' => 4],
                  ['id' => 5, 'role_id' => 1, 'permission_id' => 5],
                  ['id' => 6, 'role_id' => 1, 'permission_id' => 6],
                  ['id' => 7, 'role_id' => 1, 'permission_id' => 7],
                  ['id' => 8, 'role_id' => 1, 'permission_id' => 8],
                  ['id' => 9, 'role_id' => 1, 'permission_id' => 9],
                  ['id' => 10, 'role_id' => 1, 'permission_id' => 10],
                  ['id' => 12, 'role_id' => 1, 'permission_id' => 12],
                  ['id' => 13, 'role_id' => 1, 'permission_id' => 13],
                  ['id' => 14, 'role_id' => 1, 'permission_id' => 14],
                  ['id' => 15, 'role_id' => 1, 'permission_id' => 15],
                  ['id' => 16, 'role_id' => 2, 'permission_id' => 1],
                  ['id' => 17, 'role_id' => 2, 'permission_id' => 2],
                  ['id' => 18, 'role_id' => 2, 'permission_id' => 3],
                  ['id' => 19, 'role_id' => 2, 'permission_id' => 4],
                  ['id' => 20, 'role_id' => 2, 'permission_id' => 5],
                  ['id' => 21, 'role_id' => 2, 'permission_id' => 12],
                  ['id' => 22, 'role_id' => 2, 'permission_id' => 13],
                  ['id' => 23, 'role_id' => 2, 'permission_id' => 14],
                  ['id' => 24, 'role_id' => 2, 'permission_id' => 15],
                  ['id' => 25, 'role_id' => 2, 'permission_id' => 11],
                  ['id' => 26, 'role_id' => 3, 'permission_id' => 11],
                  ['id' => 27, 'role_id' => 1, 'permission_id' => 11]
                ];

        $role_permissions = $this->table('role_permissions');
        $role_permissions->insert($data)
                ->save();
    }
}
