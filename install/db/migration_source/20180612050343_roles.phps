<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Roles extends AbstractMigration
{
    public function up()
    {
        $roles = $this->table('{$tblprefix}roles');
        $roles->addColumn('name', 'string', ['limit' => 45])
            ->addColumn('description', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('required', 'boolean', ['default' => 0])
            ->addColumn('default_role', 'boolean', ['null' => true, 'default' => null])
            ->addIndex(['name', 'default_role'], ['unique' => true])
            ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}roles');
    }
}
