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
                $sqlmembers = "CREATE TABLE {$dbname}." . $tblprefix . "members (`id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(255) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `username_UNIQUE` (`username`), UNIQUE KEY `id_UNIQUE` (`id`), UNIQUE KEY `email_UNIQUE` (`email`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
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
                $sqladmins = "CREATE TABLE {$dbname}." . $tblprefix . "admins ( `adminid` char(23) NOT NULL DEFAULT 'uuid_short();', `userid` char(23) NOT NULL, `active` bit(1) NOT NULL DEFAULT b'0', `superadmin` bit(1) NOT NULL DEFAULT b'0', PRIMARY KEY (`adminid`,`userid`), UNIQUE KEY `adminid_UNIQUE` (`adminid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `fk_userid_admins` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
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
                $sqldeletedMembers = "CREATE TABLE {$dbname}." . $tblprefix . "deletedMembers ( `id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(65) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `id_UNIQUE` (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
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
                $sqlloginAttempts = "CREATE TABLE {$dbname}." . $tblprefix . "loginAttempts ( `ID` int(11) NOT NULL AUTO_INCREMENT, `Username` varchar(65) DEFAULT NULL, `IP` varchar(20) NOT NULL, `Attempts` int(11) NOT NULL, `LastLogin` datetime NOT NULL, PRIMARY KEY (`ID`) ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8; GO;";
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
                $sqlmemberInfo = "CREATE TABLE {$dbname}." . $tblprefix . "memberInfo ( `userid` char(23) NOT NULL, `firstname` varchar(45) NOT NULL, `lastname` varchar(55) DEFAULT NULL, `phone` varchar(20) DEFAULT NULL, `address1` varchar(45) DEFAULT NULL, `address2` varchar(45) DEFAULT NULL, `city` varchar(45) DEFAULT NULL, `state` varchar(30) DEFAULT NULL, `country` varchar(45) DEFAULT NULL, `bio` varchar(60000) DEFAULT NULL, `userimage` varchar(255) DEFAULT NULL, UNIQUE KEY `userid_UNIQUE` (`userid`), KEY `fk_userid_idx` (`userid`), CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
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
                $status = "Creating <span class='dbtable'>" . $tblprefix . "cookies</span> table";
                $sqlcookies = "CREATE TABLE {$dbname}." . $tblprefix . "cookies ( `cookieid` char(23) NOT NULL, `userid` char(23) NOT NULL, `tokenid` char(25) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`userid`), CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $cook = $conn->exec($sqlcookies);
                if ($cook) {
                    unset($cook);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>" . $tblprefix . "cookies</span> table");
                    $failure = 1;
                    break 1;
                }
            case 8:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "tokens</span> table";
                $sqltokens = "CREATE TABLE {$dbname}." . $tblprefix . "tokens ( `tokenid` char(25) NOT NULL, `userid` char(23) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`tokenid`), UNIQUE KEY `tokenid_UNIQUE` (`tokenid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `userid_t` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $tkn = $conn->exec($sqltokens);
                if ($tkn) {
                    unset($tkn);
                    break 1;
                    sleep(1);
                } else {
                    throw new \Exception("Failed to create <span class='dbtable'>" . $tblprefix . "tokens</span> table");
                    $failure = 1;
                    break 1;
                }
            case 9:
                $conn = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqldeletedMembersTrigger = "CREATE TRIGGER {$dbname}.move_to_deletedMembers\n    AFTER DELETE ON {$dbname}." . $tblprefix . "members\n    FOR EACH ROW\n    BEGIN\n        -- Delete from {$dbname}." . $tblprefix . "deletedMembers table if id exists\n        DELETE FROM {$dbname}." . $tblprefix . "deletedMembers\n        WHERE {$dbname}." . $tblprefix . "deletedMembers.id = OLD.id;\n        UPDATE {$dbname}." . $tblprefix . "admins SET {$dbname}.active =\n        '0' where {$dbname}." . $tblprefix . "admins.userid = OLD.id;\n       -- Insert record into deletedMembers table\n       INSERT INTO {$dbname}." . $tblprefix . "deletedMembers ( id, username, password, email, verified, admin)\n       VALUES ( OLD.id, OLD.username, OLD.password, OLD.email, OLD.verified, OLD.admin );\n    END; GO;";
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
            case 10:
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
            case 11:
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
            case 12:
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
            case 13:
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
            case 14:
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
