<?php

use Phinx\Migration\AbstractMigration;

class MemberJail extends AbstractMigration
{
    public function up()
    {
        $member_jail = $this->table('{$tblprefix}member_jail');
        $member_jail->addColumn('user_id', 'char', ['limit' => 23])
          ->addColumn('banned_hours', 'float', ['default' => '24'])
          ->addColumn('reason', 'string', ['default' => 2000, 'null' => true])
          ->addColumn('timestamp', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
          ->addIndex(['user_id'], ['unique' => true])
          ->addForeignKey('user_id', '{$tblprefix}members', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}member_jail');
    }
}
