<?php

use Phinx\Migration\AbstractMigration;

class MailQueue extends AbstractMigration
{
    public function up()
    {
        $queue = $this->table('{$tblprefix}mail_queue');
        $queue->addColumn('member_id', 'char', ['limit' => 23])
          ->addColumn('type', 'string', ['limit' => 45])
          ->addColumn('timestamp', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}mail_queue');
    }
}
