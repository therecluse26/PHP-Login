<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Tokens extends AbstractMigration
{
    public function up()
    {
        $tokens = $this->table('{$tblprefix}tokens', ['id' => false, 'primary_key' => 'tokenid']);
        $tokens->addColumn('tokenid', 'char', ['limit' => 25])
          ->addColumn('userid', 'char', ['limit' => 23])
          ->addColumn('expired', 'boolean', ['default' => 0])
          ->addColumn('timestamp', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
          ->addIndex(['tokenid', 'userid'], ['unique' => true])
          ->addForeignKey('userid', '{$tblprefix}members', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}tokens');
    }
}
