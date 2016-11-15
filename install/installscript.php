<?php
function installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw)
{
    $failure = 0;
    try {
        switch ($i) {
            case 0:
                require "confgen.php";
                break 1;
            case 1:
                $conn = new \PDO("mysql:host={$dbhost}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating database <span class='dbtable'>{$dbname}</span>";
                $sqlcreate = "CREATE DATABASE {$dbname};";
                $c = $conn->exec($sqlcreate);
                if ($c) {
                    unset($c);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create database");
                    $failure = 1;
                    break 1;
                }
            case 2:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "members</span> table";
                $sqlmembers = "CREATE TABLE {$dbname}." . $tblprefix . "members (\n      id char(23) NOT NULL,\n      username varchar(65) NOT NULL DEFAULT '',\n      password varchar(65) NOT NULL DEFAULT '',\n      email varchar(65) NOT NULL,\n      verified tinyint(1) NOT NULL DEFAULT '0',\n      admin tinyint(1) NOT NULL DEFAULT '0',\n      mod_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n      PRIMARY KEY (id),\n      UNIQUE KEY username_UNIQUE (username),\n      UNIQUE KEY id_UNIQUE (id),\n      UNIQUE KEY email_UNIQUE (email)\n    ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $m = $conn->exec($sqlmembers);
                if ($m) {
                    unset($m);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>" . $tblprefix . "members</span> table");
                    $failure = 1;
                    break 1;
                }
            case 3:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "admins</span> table";
                $sqladmins = "CREATE TABLE {$dbname}." . $tblprefix . "admins (\n      adminid char(23) NOT NULL,\n      userid char(23) NOT NULL,\n      active bit(1) NOT NULL DEFAULT b'0',\n      superadmin bit(1) NOT NULL DEFAULT b'0',\n      PRIMARY KEY (adminid,userid),\n      UNIQUE KEY adminid_UNIQUE (adminid),\n      UNIQUE KEY userid_UNIQUE (userid),\n      CONSTRAINT fk_userid_admins FOREIGN KEY (userid) REFERENCES " . $tblprefix . "members (id) ON UPDATE CASCADE\n    ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $a = $conn->exec($sqladmins);
                if ($a) {
                    unset($a);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>" . $tblprefix . "admins</span> table");
                    $failure = 1;
                    break 1;
                }
            case 4:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "deletedMembers</span> table";
                $sqldeletedMembers = "CREATE TABLE {$dbname}." . $tblprefix . "deletedMembers (\n      id char(23) NOT NULL,\n      username varchar(65) NOT NULL DEFAULT '',\n      password varchar(65) NOT NULL DEFAULT '',\n      email varchar(65) NOT NULL,\n      verified tinyint(1) NOT NULL DEFAULT '0',\n      admin tinyint(1) NOT NULL DEFAULT '0',\n      mod_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n      PRIMARY KEY (id),\n      UNIQUE KEY id_UNIQUE (id)\n    ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $dm = $conn->exec($sqldeletedMembers);
                if ($dm) {
                    unset($dm);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>" . $tblprefix . "deletedMembers</span> table");
                    $failure = 1;
                    break 1;
                }
            case 5:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "loginAttempts</span> table";
                $sqlloginAttempts = "CREATE TABLE {$dbname}." . $tblprefix . "loginAttempts (\n      IP varchar(20) NOT NULL,\n      Attempts int(11) NOT NULL,\n      LastLogin datetime NOT NULL,\n      Username varchar(65) DEFAULT NULL,\n      ID int(11) NOT NULL AUTO_INCREMENT,\n      PRIMARY KEY (ID)\n    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8; GO;";
                $la = $conn->exec($sqlloginAttempts);
                if ($la) {
                    unset($la);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>" . $tblprefix . "loginAttempts</span> table");
                    $failure = 1;
                    break 1;
                }
            case 6:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "memberInfo</span> table";
                $sqlmemberInfo = "CREATE TABLE {$dbname}." . $tblprefix . "memberInfo (\n      userid char(23) NOT NULL,\n      firstname varchar(45) NOT NULL,\n      lastname varchar(55) DEFAULT NULL,\n      phone varchar(20) DEFAULT NULL,\n      address1 varchar(45) DEFAULT NULL,\n      address2 varchar(45) DEFAULT NULL,\n      city varchar(45) DEFAULT NULL,\n      state varchar(30) DEFAULT NULL,\n      country varchar(45) DEFAULT NULL,\n      bio varchar(65000) DEFAULT NULL,\n      userimage blob,\n      UNIQUE KEY userid_UNIQUE (userid),\n      KEY fk_userid_idx (userid),\n      CONSTRAINT fk_userid FOREIGN KEY (userid) REFERENCES " . $tblprefix . "members\n      (id) ON DELETE CASCADE ON UPDATE NO ACTION\n    ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $mi = $conn->exec($sqlmemberInfo);
                if ($mi) {
                    unset($mi);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>" . $tblprefix . "memberInfo</span> table");
                    $failure = 1;
                    break 1;
                }
            case 7:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqldeletedMembersTrigger = "CREATE TRIGGER {$dbname}.move_to_deletedMembers\n    AFTER DELETE ON {$dbname}." . $tblprefix . "members\n    FOR EACH ROW\n    BEGIN\n        -- Delete from {$dbname}." . $tblprefix . "deletedMembers table if id exists\n        DELETE FROM {$dbname}." . $tblprefix . "deletedMembers\n        WHERE {$dbname}." . $tblprefix . "deletedMembers.id = OLD.id;\n        UPDATE {$dbname}." . $tblprefix . "admins SET {$dbname}.active =\n        '0' where {$dbname}." . $tblprefix . "admins.id = OLD.id;\n       -- Insert record into deletedMembers table\n       INSERT INTO {$dbname}." . $tblprefix . "deletedMembers ( id, username, password, email, verified, admin)\n       VALUES ( OLD.id, OLD.username, OLD.password, OLD.email, OLD.verified, OLD.admin );\n    END; GO;";
                $dmt = $conn->exec($sqldeletedMembersTrigger);
                if ($dmt) {
                    unset($dmt);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>\n        " . $tblprefix . "move_to_deletedMembers</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 8:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqladdAdminTrigger = "CREATE TRIGGER {$dbname}.add_admin\n    AFTER INSERT ON {$dbname}." . $tblprefix . "members\n    FOR EACH ROW\n    BEGIN\n    IF (NEW.admin = 1) THEN\n       -- Insert record into deletedMembers table\n       INSERT INTO {$dbname}." . $tblprefix . "admins (adminid, userid, active, superadmin )\n       VALUES (uuid_short(), NEW.id, 1, 0 );\n         END IF;\n    END; GO;";
                $aat = $conn->exec($sqladdAdminTrigger);
                if ($aat) {
                    unset($aat);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>add_admin</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 9:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqladdAdminBeforeUpdateTrigger = "CREATE TRIGGER `add_admin_beforeUpdate`\nBEFORE UPDATE ON {$dbname}." . $tblprefix . "members\n\nFOR EACH ROW\nBEGIN\nset @s = (SELECT superadmin from " . $tblprefix . "admins where userid = NEW.id);\nset @a = (SELECT adminid from " . $tblprefix . "admins where userid = NEW.id);\n    IF (NEW.admin = 1 && isnull(@a)) THEN\n       -- Insert record into deletedMembers table\n       INSERT INTO " . $tblprefix . "admins ( adminid, userid, active, superadmin )\n       VALUES ( uuid_short(), NEW.id, 1, 0 );\n    ELSEIF (NEW.admin = 0) THEN\n        IF (@s = 0) THEN\n            DELETE FROM " . $tblprefix . "admins WHERE userid = NEW.id and superadmin = 0;\n        ELSEIF (@s = 1) THEN\n            SIGNAL SQLSTATE '45000'\n            SET MESSAGE_TEXT='Cannot delete superadmin';\n        END IF;\n    END IF;\nEND;";
                $aaaut = $conn->exec($sqladdAdminBeforeUpdateTrigger);
                if ($aaaut) {
                    unset($aaaut);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>add_admin_beforeUpdate</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 10:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>cleanupOldDeleted</span> event";
                $sqlcleanupOldDeletedEvent = "SET GLOBAL event_scheduler = ON;\n    CREATE EVENT IF NOT EXISTS {$dbname}.cleanupOldDeleted\n    ON SCHEDULE\n        EVERY 1 DAY\n    COMMENT 'Removes deleted records older than 30 days.'\n    DO\n    BEGIN\n    DELETE FROM {$dbname}." . $tblprefix . "deletedMembers WHERE mod_timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY);\n    END; GO;";
                $code = $conn->exec($sqlcleanupOldDeletedEvent);
                if ($code) {
                    unset($code);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>cleanupOldDeleted<span> event");
                    $failure = 1;
                    break 1;
                }
            case 11:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating superadmin user";
                $sqlAddSuperAdmin = $conn->prepare("INSERT INTO {$dbname}." . $tblprefix . "members (id, username, password, email, verified, admin) values(:said, :superadmin, :sapw, :saemail, 1, 1)");
                $sqlAddSuperAdmin->bindParam(':said', $said);
                $sqlAddSuperAdmin->bindParam(':superadmin', $superadmin);
                $sqlAddSuperAdmin->bindParam(':sapw', $sapw);
                $sqlAddSuperAdmin->bindParam(':saemail', $saemail);
                $asa = $sqlAddSuperAdmin->execute();
                if ($asa) {
                    unset($asa);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create superadmin");
                    $failure = 1;
                    break 1;
                }
            case 12:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating superadmin user";
                $sqlEscalateSuperAdmin = $conn->prepare("UPDATE {$dbname}." . $tblprefix . "admins set superadmin = 1 where userid = :said");
                $sqlEscalateSuperAdmin->bindParam(':said', $said);
                $esa = $sqlEscalateSuperAdmin->execute();
                if ($esa) {
                    unset($esa);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create superadmin");
                    $failure = 1;
                    break 1;
                }
            default:
                $i++;
                break 1;
        }
    } catch (\Exception $e) {
        $status = "An error occurred: " . $e->getMessage();
        $failure = 1;
    }
    $returnArray = array("status" => $status, "failure" => $failure);
    return $returnArray;
}