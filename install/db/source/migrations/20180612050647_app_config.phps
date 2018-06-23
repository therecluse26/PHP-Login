<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class AppConfig extends AbstractMigration
{
    public function up()
    {
        $config = $this->table('{$tblprefix}app_config', ['id' => false]);
        $config->addColumn('setting', 'string', ['limit' => 30])
        ->addColumn('sortorder', 'integer', ['null' => true])
        ->addColumn('category', 'string', ['limit' => 25])
        ->addColumn('type', 'string', ['limit' => 15])
        ->addColumn('options', 'string', ['limit' => 255])
        ->addColumn('description', 'string', ['limit' => 140, 'null' => true])
        ->addColumn('required', 'boolean', ['default' => 0])
        ->addIndex(['setting'], ['unique' => true])
        ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}app_config');
    }
}
