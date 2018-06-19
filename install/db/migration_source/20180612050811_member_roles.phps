<?php

use Phinx\Migration\AbstractMigration;

class MemberRoles extends AbstractMigration
{
    public function up()
    {
        $member_roles = $this->table('{$tblprefix}member_roles');
        $member_roles->addColumn('member_id', 'char', ['limit' => 23])
          ->addColumn('role_id', 'integer', ['limit' => 11])
          ->addIndex(['member_id', 'role_id'], ['unique' => true])
          ->addForeignKey('member_id', '{$tblprefix}members', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
          ->addForeignKey('role_id', '{$tblprefix}roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}member_roles');
    }
}
