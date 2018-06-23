<?php

use Phinx\Migration\AbstractMigration;

class LoginAttempts extends AbstractMigration
{
    public function up()
    {
        $login_attempts = $this->table('{$tblprefix}login_attempts');
        $login_attempts->addColumn('username', 'string', ['limit' => 65])
          ->addColumn('ip', 'string', ['limit' => 20])
          ->addColumn('attempts', 'integer', ['limit' => 11])
          ->addColumn('lastlogin', 'datetime')
          ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}login_attempts');
    }
}
