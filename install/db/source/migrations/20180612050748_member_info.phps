<?php

use Phinx\Migration\AbstractMigration;

class MemberInfo extends AbstractMigration
{
    public function up()
    {
        $member_info = $this->table('{$tblprefix}member_info', ['id' => false, 'primary_key' => 'userid']);
        $member_info->addColumn('userid', 'char', ['limit' => 23])
          ->addColumn('firstname', 'string', ['limit' => 45, 'null' => true])
          ->addColumn('lastname', 'string', ['limit' => 55, 'null' => true])
          ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
          ->addColumn('address1', 'string', ['limit' => 45, 'null' => true])
          ->addColumn('address2', 'string', ['limit' => 45, 'null' => true])
          ->addColumn('city', 'string', ['limit' => 45, 'null' => true])
          ->addColumn('state', 'string', ['limit' => 30, 'null' => true])
          ->addColumn('country', 'string', ['limit' => 45, 'null' => true])
          ->addColumn('bio', 'string', ['limit' => 20000, 'null' => true])
          ->addColumn('userimage', 'string', ['limit' => 255, 'null' => true])
          ->addIndex(['userid'], ['unique' => true])
          ->addForeignKey('userid', '{$tblprefix}members', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}member_info');
    }
}
