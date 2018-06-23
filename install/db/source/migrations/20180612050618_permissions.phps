<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Permissions extends AbstractMigration
{
    public function up()
    {
        $permissions = $this->table('{$tblprefix}permissions');
        $permissions->addColumn('name', 'string', ['limit' => 100])
          ->addColumn('description', 'string', ['limit' => 255, 'null' => true])
          ->addColumn('category', 'string', ['limit' => 50, 'null' => true])
          ->addColumn('required', 'boolean', ['default' => 0])
          ->addIndex(['name'], ['unique' => true])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}permissions');
    }
}
