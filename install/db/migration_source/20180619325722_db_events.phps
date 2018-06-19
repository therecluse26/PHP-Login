<?php

use Phinx\Migration\AbstractMigration;

class DbEvents extends AbstractMigration
{
    public function up()
    {
        $this->execute("SET GLOBAL event_scheduler = ON;
                        CREATE EVENT IF NOT EXISTS cleanupOldDeleted
                        	ON SCHEDULE EVERY 1 DAY
                        DO
                        BEGIN
                          DELETE FROM {$tblprefix}deleted_members
                          WHERE mod_timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY);
                        END;");

        $this->execute("CREATE EVENT IF NOT EXISTS unbanUsers
                            ON SCHEDULE EVERY 15 MINUTE
                        DO
                        BEGIN
                            DELETE FROM `vw_banned_users` where hours_remaining < 0;
                            UPDATE {$tblprefix}members m SET m.banned = 0 where m.banned = 1 AND m.id not in (select v.user_id from `vw_banned_users` v);
                        END;");
    }

    public function down()
    {
        $this->execute("DROP EVENT IF EXISTS cleanupOldDeleted;");
        $this->execute("DROP EVENT IF EXISTS unbanUsers;");
    }
}
