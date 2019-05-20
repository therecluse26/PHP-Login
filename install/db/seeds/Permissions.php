<?php


use Phinx\Seed\AbstractSeed;

class Permissions extends AbstractSeed
{
    public function run()
    {
        $data = [
                [
                    'id'    => 1,
                    'name' => 'Verify Users',
                    'description' => 'Administration permission allowing for the verification of new users',
                    'category' => 'Users',
                    'required' => 1
                ],
                [
                    'id'    => 2,
                    'name' => 'Delete Unverified Users',
                    'description' => 'Administration permission allowing the deletion of unverified users',
                    'category' => 'Users',
                    'required' => 1
                ],
                [
                    'id'    => 3,
                    'name' => 'Ban Users',
                    'description' => 'Moderation permission allowing the banning of users',
                    'category' => 'Users',
                    'required' => 1
                ],
                [
                    'id'    => 4,
                    'name' => 'Assign Roles to Users',
                    'description' => 'Administration permission allowing the assignment of roles to users',
                    'category' => 'Users',
                    'required' => 1
                ],
                [
                    'id'    => 5,
                    'name' => 'Assign Users to Roles',
                    'description' => 'Administration permission allowing the assignment of users to roles',
                    'category' => 'Roles',
                    'required' => 1
                ],
                [
                    'id'    => 6,
                    'name' => 'Create Roles',
                    'description' => 'Administration permission allowing for the creation of new roles',
                    'category' => 'Roles',
                    'required' => 1
                ],
                [
                    'id'    => 7,
                    'name' => 'Delete Roles',
                    'description' => 'Administration permission allowing for the deletion of roles',
                    'category' => 'Roles',
                    'required' => 1
                ],
                [
                    'id'    => 8,
                    'name' => 'Create Permissions',
                    'description' => 'Administration permission allowing for the creation of new permissions',
                    'category' => 'Permissions',
                    'required' => 1
                ],
                [
                    'id'    => 9,
                    'name' => 'Delete Permissions',
                    'description' => 'Administration permission allowing for the deletion of permissions',
                    'category' => 'Permissions',
                    'required' => 1
                ],
                [
                    'id'    => 10,
                    'name' => 'Assign Permissions to Roles',
                    'description' => 'Administration permission allowing the assignment of permissions to roles',
                    'category' => 'Roles',
                    'required' => 1
                ],
                [
                    'id'    => 11,
                    'name' => 'Edit Site Config',
                    'description' => 'Administration permission allowing the editing of core site configuration (dangerous)',
                    'category' => 'Administration',
                    'required' => 1
                ],
                [
                    'id'    => 12,
                    'name' => 'View Permissions',
                    'description' => 'Administration permission allowing the viewing of all permissions',
                    'category' => 'Permissions',
                    'required' => 1
                ],
                [
                    'id'    => 13,
                    'name' => 'View Roles',
                    'description' => 'Administration permission allowing for the viewing of all roles',
                    'category' => 'Roles',
                    'required' => 1
                ],
                [
                    'id'    => 14,
                    'name' => 'View Users',
                    'description' => 'Administration permission allowing for the viewing of all users',
                    'category' => 'Users',
                    'required' => 1
                ],
                [
                    'id'    => 15,
                    'name' => 'Delete Users',
                    'description' => 'Administration permission allowing for the deletion of users',
                    'category' => 'Users',
                    'required' => 1
                ]
            ];

        $permissions = $this->table('permissions');
        $permissions->insert($data)
                  ->save();
    }
}
