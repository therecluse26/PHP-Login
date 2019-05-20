<?php

use Phinx\Seed\AbstractSeed;

class MemberRoles extends AbstractSeed
{
    public function getDependencies()
    {
        return [
              'Members',
              'Roles'
          ];
    }

    public function run()
    {
        $data = [
            [
                'id'    => 1,
                'member_id' => '21072721585b2001256a2ac',
                'role_id' => 1
            ]
        ];

        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $member_roles = $this->table('member_roles');
        $member_roles->insert($data)
              ->save();

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
