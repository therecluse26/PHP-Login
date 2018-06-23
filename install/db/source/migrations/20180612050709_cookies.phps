<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Cookies extends AbstractMigration
{
    public function up()
    {
        $cookies = $this->table('{$tblprefix}cookies', ['id' => false, 'primary_key' => 'cookieid']);
        $cookies->addColumn('cookieid', 'char', ['limit' => 23])
          ->addColumn('userid', 'char', ['limit' => 23])
          ->addColumn('tokenid', 'char', ['limit' => 23])
          ->addColumn('expired', 'boolean', ['default' => 0])
          ->addColumn('timestamp', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
          ->addIndex(['cookieid', 'userid'], ['unique' => true])
          ->addForeignKey('userid', '{$tblprefix}members', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}cookies');
    }
}
