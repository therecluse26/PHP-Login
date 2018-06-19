<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class DeletedMembers extends AbstractMigration
{
    public function up()
    {
        $deleted_members = $this->table('{$tblprefix}deleted_members', ['id' => false]);
        $deleted_members->addColumn('id', 'char', ['limit' => 23])
          ->addColumn('username', 'string', ['limit' => 65])
          ->addColumn('password', 'string', ['limit' => 255])
          ->addColumn('email', 'string', ['limit' => 65])
          ->addColumn('verified', 'boolean', ['default' => 0])
          ->addColumn('banned', 'boolean', ['default' => 0])
          ->addColumn('mod_timestamp', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
          ->addIndex(['id'], ['unique' => true])
          ->save();
    }
    public function down()
    {
        $this->dropTable('{$tblprefix}deleted_members');
    }
}
