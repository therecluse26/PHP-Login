<?php


use Phinx\Seed\AbstractSeed;

class Roles extends AbstractSeed
{
    public function run()
    {
        $data = [
                  [
                      'id'    => 1,
                      'name' => 'Superadmin',
                      'description' => 'Master administrator of site',
                      'required' => 1
                  ],
                  [
                      'id'    => 2,
                      'name' => 'Admin',
                      'description' => 'Site administrator',
                      'required' => 1
                  ],
                  [
                      'id'    => 3,
                      'name' => 'Standard User',
                      'description' => 'Default site role for standard users',
                      'required' => 1,
                      'default_role' => 1
                  ]
              ];

        $roles = $this->table('roles');
        $roles->insert($data)
                    ->save();
    }
}
