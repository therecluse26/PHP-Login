<?php
function installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw, $settingsArr = '')
{
    $status = '';
    $failure = 0;
    try {
        switch ($i) {
            // Create Database
            case 0:
                try {
                    $conn = new PDO("mysql:host={$dbhost}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating database <span class='dbtable'>{$dbname}</span>";
                    $sqlcreate = "CREATE DATABASE IF NOT EXISTS {$dbname} DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;";
                    $c = $conn->exec($sqlcreate);

                    unset($c);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create database: ". $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 1:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "members</span> table";
                    $sqlmembers = "SET FOREIGN_KEY_CHECKS= 0; CREATE TABLE IF NOT EXISTS {$tblprefix}members (`id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(255) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL DEFAULT '', `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `username_UNIQUE` (`username`), UNIQUE KEY `id_UNIQUE` (`id`), UNIQUE KEY `email_UNIQUE` (`email`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $m = $conn->exec($sqlmembers);

                    unset($m);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "members</span> table." . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 2:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "admins</span> table";
                    $sqladmins = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}admins ( `adminid` char(23) NOT NULL DEFAULT 'uuid_short();', `userid` char(23) NOT NULL, `active` tinyint(1) NOT NULL DEFAULT b'0', `superadmin` tinyint(1) NOT NULL DEFAULT b'0', PRIMARY KEY (`adminid`,`userid`), UNIQUE KEY `adminid_UNIQUE` (`adminid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `fk_userid_admins` FOREIGN KEY (`userid`) REFERENCES `{$tblprefix}members` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $a = $conn->exec($sqladmins);

                    unset($a);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "admins</span> table. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 3:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "deleted_members</span> table";
                    $sqldeleted_members = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}deleted_members ( `id` char(23) NOT NULL, `username` varchar(65) NOT NULL DEFAULT '', `password` varchar(65) NOT NULL DEFAULT '', `email` varchar(65) NOT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `admin` tinyint(1) NOT NULL DEFAULT '0', `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `id_UNIQUE` (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $dm = $conn->exec($sqldeleted_members);

                    unset($dm);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "deleted_members</span> table. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 4:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "login_attempts</span> table";
                    $sqllogin_attempts = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}login_attempts ( `ID` int(11) NOT NULL AUTO_INCREMENT, `Username` varchar(65) DEFAULT NULL, `IP` varchar(20) NOT NULL, `Attempts` int(11) NOT NULL, `LastLogin` datetime NOT NULL, PRIMARY KEY (`ID`) ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $la = $conn->exec($sqllogin_attempts);

                    unset($la);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "login_attempts</span> table." . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 5:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "member_info</span> table";
                    $sqlmember_info = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}member_info ( `userid` char(23) NOT NULL, `firstname` varchar(45) NOT NULL, `lastname` varchar(55) DEFAULT NULL, `phone` varchar(20) DEFAULT NULL, `address1` varchar(45) DEFAULT NULL, `address2` varchar(45) DEFAULT NULL, `city` varchar(45) DEFAULT NULL, `state` varchar(30) DEFAULT NULL, `country` varchar(45) DEFAULT NULL, `bio` varchar(20000) DEFAULT NULL, `userimage` varchar(255) DEFAULT NULL, UNIQUE KEY `userid_UNIQUE` (`userid`), KEY `fk_userid_idx` (`userid`), CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ) ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $mi = $conn->exec($sqlmember_info);

                    unset($mi);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "member_info</span> table. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 6:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "cookies</span> table";
                    $sqlcookies = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}cookies ( `cookieid` char(23) NOT NULL, `userid` char(23) NOT NULL, `tokenid` char(25) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`userid`), CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $cook = $conn->exec($sqlcookies);

                    unset($cook);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "cookies</span> table.". $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 7:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "tokens</span> table";
                    $sqltokens = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}tokens ( `tokenid` char(25) NOT NULL, `userid` char(23) NOT NULL, `expired` tinyint(1) NOT NULL DEFAULT '0', `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`tokenid`), UNIQUE KEY `tokenid_UNIQUE` (`tokenid`), UNIQUE KEY `userid_UNIQUE` (`userid`), CONSTRAINT `userid_t` FOREIGN KEY (`userid`) REFERENCES `{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $tkn = $conn->exec($sqltokens);
                    unset($tkn);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "tokens</span> table. ". $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 8:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "app_config</span> table";
                    $sqlconfig = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}app_config (`setting` char(26) NOT NULL, `value` varchar(12000) NOT NULL, `sortorder` int(5), `category` varchar(25) NOT NULL, `type` varchar(15) NOT NULL, `description` varchar(140), `required` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`setting`), UNIQUE KEY `setting_UNIQUE` (`setting`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $cnf = $conn->exec($sqlconfig);

                    unset($cnf);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "app_config</span> table. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 9:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating triggers";
                    $sqldrtrig = "DROP TRIGGER IF EXISTS move_to_deleted_members; DROP TRIGGER IF EXISTS add_admin; DROP TRIGGER IF EXISTS add_admin_beforeUpdate; DROP TRIGGER IF EXISTS stop_delete_required;";
                    $drtr = $conn->exec($sqldrtrig);
                    //$drtr = "blah";
                    unset($drtr);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 10:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating triggers";
                    $sqldeleted_membersTrigger = "CREATE TRIGGER move_to_deleted_members AFTER DELETE ON {$tblprefix}members FOR EACH ROW BEGIN DELETE FROM {$tblprefix}deleted_members WHERE {$tblprefix}deleted_members.id = OLD.id; UPDATE {$tblprefix}admins SET active = '0' where {$tblprefix}admins.userid = OLD.id;  INSERT INTO {$tblprefix}deleted_members ( id, username, password, email, verified, admin) VALUES ( OLD.id, OLD.username, OLD.password, OLD.email, OLD.verified, OLD.admin ); END;";
                    $dmt = $conn->exec($sqldeleted_membersTrigger);
                    unset($dmt);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 11:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating triggers";
                    $sqladdAdminTrigger = "CREATE TRIGGER add_admin AFTER INSERT ON {$tblprefix}members FOR EACH ROW BEGIN IF (NEW.admin = 1) THEN  INSERT INTO {$tblprefix}admins (adminid, userid, active, superadmin ) VALUES (uuid_short(), NEW.id, 1, 0 ); END IF; END;";
                    $aat = $conn->exec($sqladdAdminTrigger);

                    unset($aat);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>add_admin</span> trigger. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 12:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating triggers";
                    $sqladdAdminBeforeUpdateTrigger = "CREATE TRIGGER add_admin_beforeUpdate BEFORE UPDATE ON {$tblprefix}members FOR EACH ROW BEGIN set @s = (SELECT superadmin from {$tblprefix}admins where userid = NEW.id); set @a = (SELECT adminid from {$tblprefix}admins where userid = NEW.id); IF (NEW.admin = 1 && isnull(@a)) THEN INSERT INTO {$tblprefix}admins ( adminid, userid, active, superadmin ) VALUES ( uuid_short(), NEW.id, 1, 0 ); ELSEIF (NEW.admin = 0) THEN IF (@s = 0) THEN DELETE FROM {$tblprefix}admins WHERE userid = NEW.id and superadmin = 0; ELSEIF (@s = 1) THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Cannot delete superadmin'; END IF; END IF; END;";
                    $aaaut = $conn->exec($sqladdAdminBeforeUpdateTrigger);

                    unset($aaaut);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>add_admin_beforeUpdate</span> trigger. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 13:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>cleanupOldDeleted</span> event";
                    $sqlcleanupOldDeletedEvent = "SET GLOBAL event_scheduler = ON;  CREATE EVENT IF NOT EXISTS cleanupOldDeleted ON SCHEDULE EVERY 1 DAY COMMENT 'Removes deleted records older than 30 days.' DO BEGIN DELETE FROM {$tblprefix}deleted_members WHERE mod_timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY); END;";
                    $code = $conn->exec($sqlcleanupOldDeletedEvent);

                    unset($code);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>cleanupOldDeleted<span> event. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 14:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating superadmin user";
                    $sqlAddSuperAdmin = $conn->prepare("SET FOREIGN_KEY_CHECKS= 0; REPLACE INTO {$tblprefix}members (id, username, password, email, verified, admin) values(:said, :superadmin, :sapw, :saemail, 1, 1); SET FOREIGN_KEY_CHECKS= 1;");
                    $sqlAddSuperAdmin->bindParam(':said', $said);
                    $sqlAddSuperAdmin->bindParam(':superadmin', $superadmin);
                    $sqlAddSuperAdmin->bindParam(':sapw', $sapw);
                    $sqlAddSuperAdmin->bindParam(':saemail', $saemail);
                    $asa = $sqlAddSuperAdmin->execute();

                    unset($asa);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create superadmin. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 15:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating superadmin user";
                    $sqlEscalateSuperAdmin = $conn->prepare("SET FOREIGN_KEY_CHECKS = 0; UPDATE {$tblprefix}admins set superadmin = 1 where userid = :said; SET FOREIGN_KEY_CHECKS = 1;");
                    $sqlEscalateSuperAdmin->bindParam(':said', $said);
                    $esa = $sqlEscalateSuperAdmin->execute();

                    unset($esa);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create superadmin. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 16:
                try {
                    //INSERT APP SETTINGS
                    function func_enabled($function)
                    {
                        $disabled = explode(',', ini_get('disable_functions'));
                        return !in_array($function, $disabled);
                    }
                    if (func_enabled('shell_exec')) {
                        if (substr(PHP_OS, 0, 3) != 'WIN') {
                            if (!shell_exec('which curl')) {
                                $curl_enabled = 'false';
                            } else {
                                $curl_enabled = 'true';
                            }
                        } else {
                            if (!shell_exec('where curl')) {
                                $curl_enabled = 'false';
                            } else {
                                $curl_enabled = 'true';
                            }
                        }
                    } else {
                        $curl_enabled = 'false';
                    }
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating application configuration settings";

                    $status = "Creating application settings";
                    $values = "";

                    $sqlappsettings = "REPLACE INTO {$tblprefix}app_config (`sortorder`,`setting`,`category`,`value`,`type`,`description`,`required`) VALUES (27,'active_email','Messages','Your new account is now active! Click this link to log in!','text','Email message when account is verified',1), (26,'active_msg','Messages','Your account has been verified!','text','Display message when account is verified',1), (21,'admin_verify','Security','false','boolean','Require admin verification',1), (6,'avatar_dir','Website','/user/avatars','text','Directory where user avatars should be stored inside of base site directory. Do not include base_dir path.',1), (2,'base_dir','Website','".addslashes($settingsArr['base_dir'])."','hidden','Base directory of website in filesystem. \"C:\\...\" in windows, \"/...\" in unix systems',1), (3,'base_url','Website','".$settingsArr['base_url']."','url','Base URL of website. Example: \"http://sitename.com\"',1), (19,'cookie_expire_seconds','Security','2592000','number','Cookie expiration (in seconds)',1), (13,'from_email','Mailer','','email','From email address. Should typically be the same as \"mail_user\" email.',1), (14,'from_name','Mailer','Test Website','text','Name that shows up in \"from\" field of emails',1), (4,'htmlhead','Website','<!DOCTYPE html><html lang=\'en\'><head><meta charset=\'utf-8\'><meta name=\'viewport\' content-width=\'device-width\', initial-scale=\'1\', shrink-to-fit=\'no\'>','textarea','Main HTML header of website (without login-specific includes and script tags). Do not close <html> tag! Will break application functionality',1), (20,'jwt_secret','Security','php-login','text','Secret for JWT for tokens (Can be anything)',1), (18,'login_timeout','Security','300','number','Cooloff time for too many failed logins (in seconds)',1), (12,'mail_port','Mailer','587','number','Mail port. Common settings are 465 for ssl, 587 for tls, 25 for other',1), (10,'mail_pw','Mailer','','password','Email password to authenticate mailer',1), (11,'mail_security','Mailer','tls','text','Mail security type. Possible values are \"ssl\", \"tls\" or leave blank',1), (8,'mail_server','Mailer','','text','Mail server address. Example: \"smtp.email.com\"',1), (7,'mail_server_type','Mailer','smtp','text','Type of email server. SMTP is most typical. Other server types untested.',1), (9,'mail_user','Mailer','','email','Email user',1), (5,'mainlogo','Website','','url','URL of main site logo. Example \"http://sitename.com/logo.jpg\"',1), (17,'max_attempts','Security','5','number','Maximum login attempts',1), (16,'password_min_length','Security','6','number','Minimum password length if \"password_policy_enforce\" is set to true',1), (15,'password_policy_enforce','Security','true','boolean','Require a mixture of upper and lowercase letters and minimum password length (set by \"password_min_length\")',1), (28,'reset_email','Messages','Click the link below to reset your password','text','Email message when user wants to reset their password',1), (23,'signup_requires_admin','Messages','Thank you for signing up! Before you can login, your account needs to be activated by an administrator.','text','Message displayed when user signs up, but requires admin approval',1), (22,'signup_thanks','Messages','Thank you for signing up! You will receive an email shortly confirming the verification of your account.','text','Message displayed wehn user signs up and can verify themselves via email',1), (1,'site_name','Website','".$settingsArr['site_name']."','text','Website name',1), (24,'verify_email_admin','Messages','Thank you for signing up! Your account will be reviewed by an admin shortly','text','Email message when account requires admin verification',1), (25,'verify_email_noadmin','Messages','Click this link to verify your new account!','text','Email message when user can verify themselves',1), (29, 'curl_enabled','Website','".$curl_enabled."', 'boolean','Enable curl for various processes such as background email sending', 1), (30, 'email_working','Mailer','false', 'hidden','Indicates if email settings are correct and can connect to a mail server', 1), (31, 'admin_email','Website','".$saemail."', 'text','Site administrator email address', 1),(32, 'timezone', 'Website', '".date_default_timezone_get()."', 'timezone', 'Server time zone', 1),(33, 'token_validity','Security','24','number','Token validity in Hours (default 24 hours)','1');";

                    $dm = $conn->exec($sqlappsettings);

                    unset($dm);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create application settings. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
                case 17:
                try {
                    //Create MailLog Table
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating mail log";
                    $values = "";

                    $sqlappsettings = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}mail_log (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `type` varchar(45) NOT NULL DEFAULT 'generic',
                      `status` varchar(45) DEFAULT NULL,
                      `recipient` varchar(5000) DEFAULT NULL,
                      `response` mediumtext NOT NULL,
                      `isread` tinyint(1) NOT NULL DEFAULT b'0',
                      `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";

                    $dm = $conn->exec($sqlappsettings);

                    unset($dm);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create application settings. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 18:
                require "confgen.php";
                break 1;

            case 19:
                try {
                    //Change file permissions
                    $status = "Changing file permissions";

                    chmod($settingsArr['base_dir'] . '/login/dbconf.php', 0660);

                    break 1;
                    sleep(1.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create application settings. " . $e->getMessage());
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
