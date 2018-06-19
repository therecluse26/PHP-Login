<?php

use Phinx\Migration\AbstractMigration;

class DbViews extends AbstractMigration
{
    public function up()
    {
        $this->execute("CREATE VIEW vw_banned_users AS
                        SELECT
                            {$tblprefix}member_jail.user_id AS user_id,
                            {$tblprefix}member_jail.timestamp AS banned_timestamp,
                            {$tblprefix}member_jail.banned_hours AS banned_hours,
                            ({$tblprefix}member_jail.banned_hours - (TIME_TO_SEC(TIMEDIFF(NOW(), {$tblprefix}member_jail.timestamp)) / 3600)) AS hours_remaining
                        FROM
                            {$tblprefix}member_jail");
    }

    public function down()
    {
        $this->execute("DROP VIEW IF EXISTS vw_banned_users;");
    }
}
