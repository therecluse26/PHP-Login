<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class MailLog extends AbstractMigration
{
    public function up()
    {
        $mail_log = $this->table('{$tblprefix}mail_log');
        $mail_log->addColumn('type', 'string', ['limit' => 45, 'default' => 'generic'])
          ->addColumn('status', 'string', ['limit' => 45])
          ->addColumn('recipient', 'string', ['default' => 5000, 'null' => true])
          ->addColumn('response', 'text', ['limit' => MysqlAdapter::TEXT_MEDIUM])
          ->addColumn('is_read', 'boolean', ['default' => 0])
          ->addColumn('timestamp', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}mail_log');
    }
}
