<?php
function installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw, $settingsArr = '')
{
    $status = '';
    $failure = 0;
    try {
        switch ($i) {
            case 0:
                require "confgen.php";
                //$status = "Generating config.php file";
                //$i++;
                break 1;
            case 1:
                $conn = new PDO("mysql:host={$dbhost}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating database <span class='dbtable'>{$dbname}</span>";
                $sqlcreate = "CREATE DATABASE {$dbname};";
                $c = $conn->exec($sqlcreate);
                if ($c) {
                    unset($c);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create database");
                    $failure = 1;
                    break 1;
                }
            case 2:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "members</span> table";
                $sqlmembers = "CREATE TABLE {$dbname}.{$tblprefix}members (`id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(255) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `username_UNIQUE` (`username`), UNIQUE KEY `id_UNIQUE` (`id`), UNIQUE KEY `email_UNIQUE` (`email`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $m = $conn->exec($sqlmembers);
                if ($m) {
                    unset($m);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "members</span> table");
                    $failure = 1;
                    break 1;
                }
            case 3:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "admins</span> table";
                $sqladmins = "CREATE TABLE {$dbname}.{$tblprefix}admins ( `adminid` char(23) NOT NULL DEFAULT 'uuid_short();', `userid` char(23) NOT NULL, `active` bit(1) NOT NULL DEFAULT b'0', `superadmin` bit(1) NOT NULL DEFAULT b'0', PRIMARY KEY (`adminid`,`userid`), UNIQUE KEY `adminid_UNIQUE` (`adminid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `fk_userid_admins` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $a = $conn->exec($sqladmins);
                if ($a) {
                    unset($a);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "admins</span> table");
                    $failure = 1;
                    break 1;
                }
            case 4:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "deletedMembers</span> table";
                $sqldeletedMembers = "CREATE TABLE {$dbname}.{$tblprefix}deletedMembers ( `id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(65) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `id_UNIQUE` (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $dm = $conn->exec($sqldeletedMembers);
                if ($dm) {
                    unset($dm);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "deletedMembers</span> table");
                    $failure = 1;
                    break 1;
                }
            case 5:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "loginAttempts</span> table";
                $sqlloginAttempts = "CREATE TABLE {$dbname}.{$tblprefix}loginAttempts ( `ID` int(11) NOT NULL AUTO_INCREMENT, `Username` varchar(65) DEFAULT NULL, `IP` varchar(20) NOT NULL, `Attempts` int(11) NOT NULL, `LastLogin` datetime NOT NULL, PRIMARY KEY (`ID`) ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8; GO;";
                $la = $conn->exec($sqlloginAttempts);
                if ($la) {
                    unset($la);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "loginAttempts</span> table");
                    $failure = 1;
                    break 1;
                }
            case 6:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "memberInfo</span> table";
                $sqlmemberInfo = "CREATE TABLE {$dbname}.{$tblprefix}memberInfo ( `userid` char(23) NOT NULL, `firstname` varchar(45) NOT NULL, `lastname` varchar(55) DEFAULT NULL, `phone` varchar(20) DEFAULT NULL, `address1` varchar(45) DEFAULT NULL, `address2` varchar(45) DEFAULT NULL, `city` varchar(45) DEFAULT NULL, `state` varchar(30) DEFAULT NULL, `country` varchar(45) DEFAULT NULL, `bio` varchar(60000) DEFAULT NULL, `userimage` varchar(255) DEFAULT NULL, UNIQUE KEY `userid_UNIQUE` (`userid`), KEY `fk_userid_idx` (`userid`), CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $mi = $conn->exec($sqlmemberInfo);
                if ($mi) {
                    unset($mi);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "memberInfo</span> table");
                    $failure = 1;
                    break 1;
                }
            case 7:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "cookies</span> table";
                $sqlcookies = "CREATE TABLE {$dbname}.{$tblprefix}cookies ( `cookieid` char(23) NOT NULL, `userid` char(23) NOT NULL, `tokenid` char(25) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`userid`), CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $cook = $conn->exec($sqlcookies);
                if ($cook) {
                    unset($cook);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "cookies</span> table");
                    $failure = 1;
                    break 1;
                }
            case 8:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "tokens</span> table";
                $sqltokens = "CREATE TABLE {$dbname}.{$tblprefix}tokens ( `tokenid` char(25) NOT NULL, `userid` char(23) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`tokenid`), UNIQUE KEY `tokenid_UNIQUE` (`tokenid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `userid_t` FOREIGN KEY (`userid`) REFERENCES {$dbname}.`{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $tkn = $conn->exec($sqltokens);
                if ($tkn) {
                    unset($tkn);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "tokens</span> table");
                    $failure = 1;
                    break 1;
                }
            case 9:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>" . $tblprefix . "appConfig</span> table";
                $sqlconfig = "CREATE TABLE {$dbname}.{$tblprefix}appConfig (`setting` char(26) NOT NULL, `value` varchar(12000) NOT NULL, `required` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`setting`), UNIQUE KEY `setting_UNIQUE` (`setting`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1; GO;";
                $cnf = $conn->exec($sqlconfig);
                if ($cnf) {
                    unset($cnf);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "appConfig</span> table");
                    $failure = 1;
                    break 1;
                }
            case 10:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqldeletedMembersTrigger = "CREATE TRIGGER {$dbname}.move_to_deletedMembers AFTER DELETE ON {$dbname}.{$tblprefix}members FOR EACH ROW BEGIN  DELETE FROM {$dbname}.{$tblprefix}deletedMembers WHERE {$dbname}.{$tblprefix}deletedMembers.id = OLD.id; UPDATE {$dbname}.{$tblprefix}admins SET active = '0' where {$dbname}.{$tblprefix}admins.userid = OLD.id;  INSERT INTO {$dbname}.{$tblprefix}deletedMembers ( id, username, password, email, verified, admin) VALUES ( OLD.id, OLD.username, OLD.password, OLD.email, OLD.verified, OLD.admin ); END; GO;";
                $dmt = $conn->exec($sqldeletedMembersTrigger);
                if ($dmt) {
                    unset($dmt);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "move_to_deletedMembers</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 11:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqladdAdminTrigger = "CREATE TRIGGER {$dbname}.add_admin AFTER INSERT ON {$dbname}.{$tblprefix}members FOR EACH ROW BEGIN IF (NEW.admin = 1) THEN  INSERT INTO {$dbname}.{$tblprefix}admins (adminid, userid, active, superadmin ) VALUES (uuid_short(), NEW.id, 1, 0 ); END IF; END; GO;";
                $aat = $conn->exec($sqladdAdminTrigger);

                if ($aat) {
                    unset($aat);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>add_admin</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 12:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqladdAdminBeforeUpdateTrigger = "CREATE TRIGGER  {$dbname}.add_admin_beforeUpdate BEFORE UPDATE ON {$dbname}.{$tblprefix}members FOR EACH ROW BEGIN set @s = (SELECT superadmin from {$dbname}.{$tblprefix}admins where userid = NEW.id); set @a = (SELECT adminid from {$dbname}.{$tblprefix}admins where userid = NEW.id); IF (NEW.admin = 1 && isnull(@a)) THEN INSERT INTO {$dbname}.{$tblprefix}admins ( adminid, userid, active, superadmin ) VALUES ( uuid_short(), NEW.id, 1, 0 ); ELSEIF (NEW.admin = 0) THEN IF (@s = 0) THEN DELETE FROM {$dbname}.{$tblprefix}admins WHERE userid = NEW.id and superadmin = 0; ELSEIF (@s = 1) THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Cannot delete superadmin'; END IF; END IF; END; GO;";
                $aaaut = $conn->exec($sqladdAdminBeforeUpdateTrigger);
                if ($aaaut) {
                    unset($aaaut);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>add_admin_beforeUpdate</span> trigger");
                    $failure = 1;
                    break 1;
                }

            case 13:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating triggers";
                $sqlStopDeleteTrigger = "CREATE TRIGGER  {$dbname}.stop_delete_required BEFORE DELETE ON {$dbname}.{$tblprefix}appConfig FOR EACH ROW BEGIN IF OLD.required = 1 THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot delete required settings'; END IF; END; GO;";
                $sdt = $conn->exec($sqlStopDeleteTrigger);
                if ($sdt) {
                    unset($sdt);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>stop_delete_required</span> trigger");
                    $failure = 1;
                    break 1;
                }
            case 14:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating <span class='dbtable'>cleanupOldDeleted</span> event";
                $sqlcleanupOldDeletedEvent = "SET GLOBAL event_scheduler = ON;  CREATE EVENT IF NOT EXISTS {$dbname}.cleanupOldDeleted ON SCHEDULE EVERY 1 DAY COMMENT 'Removes deleted records older than 30 days.' DO BEGIN DELETE FROM {$dbname}.{$tblprefix}deletedMembers WHERE mod_timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY); END; GO;";
                $code = $conn->exec($sqlcleanupOldDeletedEvent);
                if ($code) {
                    unset($code);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create <span class='dbtable'>cleanupOldDeleted<span> event");
                    $failure = 1;
                    break 1;
                }
            case 15:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating superadmin user";
                $sqlAddSuperAdmin = $conn->prepare("INSERT INTO {$dbname}.{$tblprefix}members (id, username, password, email, verified, admin) values(:said, :superadmin, :sapw, :saemail, 1, 1)");
                $sqlAddSuperAdmin->bindParam(':said', $said);
                $sqlAddSuperAdmin->bindParam(':superadmin', $superadmin);
                $sqlAddSuperAdmin->bindParam(':sapw', $sapw);
                $sqlAddSuperAdmin->bindParam(':saemail', $saemail);
                $asa = $sqlAddSuperAdmin->execute();
                if ($asa) {
                    unset($asa);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create superadmin");
                    $failure = 1;
                    break 1;
                }
            case 16:
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating superadmin user";
                $sqlEscalateSuperAdmin = $conn->prepare("UPDATE {$dbname}.{$tblprefix}admins set superadmin = 1 where userid = :said");
                $sqlEscalateSuperAdmin->bindParam(':said', $said);
                $esa = $sqlEscalateSuperAdmin->execute();
                if ($esa) {
                    unset($esa);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create superadmin");
                    $failure = 1;
                    break 1;
                }

            case 17:
                //INSERT APP SETTINGS
                $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $status = "Creating application configuration settings";

                $status = "Creating application settings";
                $values = "";
                foreach ($settingsArr as $key=>$value){
                    $values = $values . "('{$key}', '{$value}', 1),";
                }
                $valinsert = rtrim($values, ',');

                $sqlappsettings = "INSERT INTO {$dbname}.{$tblprefix}appConfig (sortorder, setting, category, required, ) VALUES {$valinsert}";


                $dm = $conn->exec($sqlappsettings);
                if ($dm) {
                    unset($dm);
                    break 1;
                    sleep(0.5);
                } else {
                    throw new Exception("Failed to create application settings");
                    $failure = 1;
                    break 1;
                }

            default:
                $i++;
                break 1;
        }
    } catch (Exception $e) {
        $status = "An error occurred: " . $e->getMessage();
        $failure = 1;
    }
    $returnArray = array("status" => $status, "failure" => $failure);
    return $returnArray;
}
