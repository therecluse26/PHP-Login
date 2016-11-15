CREATE DATABASE `login`; -- Change to desired DB name

USE `login`; -- Make sure to change this if you have changed above

CREATE TABLE `members` (
  `id` char(23) NOT NULL,
  `username` varchar(65) NOT NULL DEFAULT '',
  `password` varchar(65) NOT NULL DEFAULT '',
  `email` varchar(65) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `admins` (
  `adminid` char(23) NOT NULL DEFAULT 'uuid_short();',
  `userid` char(23) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'0',
  `superadmin` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`adminid`,`userid`),
  UNIQUE KEY `adminid_UNIQUE` (`adminid`),
  UNIQUE KEY `userid_UNIQUE` (`userid`),
  CONSTRAINT `fk_userid_admins` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `deletedMembers` (
  `id` char(23) NOT NULL,
  `username` varchar(65) NOT NULL DEFAULT '',
  `password` varchar(65) NOT NULL DEFAULT '',
  `email` varchar(65) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `loginAttempts` (
  `IP` varchar(20) NOT NULL,
  `Attempts` int(11) NOT NULL,
  `LastLogin` datetime NOT NULL,
  `Username` varchar(65) DEFAULT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE `memberInfo` (
  `userid` char(23) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(55) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address1` varchar(45) DEFAULT NULL,
  `address2` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `bio` varchar(65000) DEFAULT NULL,
  `userimage` blob,
  UNIQUE KEY `userid_UNIQUE` (`userid`),
  KEY `fk_userid_idx` (`userid`),
  CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET GLOBAL event_scheduler = ON;

CREATE EVENT IF NOT EXISTS `cleanupOldDeleted`
ON SCHEDULE
    EVERY 1 DAY
COMMENT 'Removes deleted records older than 30 days.'
DO
    BEGIN
    DELETE FROM deletedMembers WHERE mod_timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY);
    END;

CREATE TRIGGER `move_to_deletedMembers`
AFTER DELETE ON `members`
FOR EACH ROW
BEGIN
    -- Delete from deletedMembers table if id exists
    DELETE FROM deletedMembers WHERE deletedMembers.id = OLD.id;
    UPDATE admins SET active = '0' where admins.id = OLD.id;
   -- Insert record into deletedMembers table
   INSERT INTO deletedMembers ( id, username, password, email, verified, admin)
   VALUES ( OLD.id, OLD.username, OLD.password, OLD.email, OLD.verified, OLD.admin );
END;


CREATE TRIGGER `add_admin`
AFTER INSERT ON `members`
FOR EACH ROW
BEGIN
IF (NEW.admin = 1) THEN
   -- Insert record into deletedMembers table
   INSERT INTO admins ( userid, active, superadmin )
   VALUES ( NEW.id, 1, 0 );
     END IF;
END;

CREATE TRIGGER `add_admin_beforeUpdate`
BEFORE UPDATE ON `members`
FOR EACH ROW
BEGIN
set @s = (SELECT superadmin from admins where userid = NEW.id);
set @a = (SELECT adminid from admins where userid = NEW.id);
    IF (NEW.admin = 1 && isnull(@a)) THEN
       -- Insert record into deletedMembers table
       INSERT INTO admins ( adminid, userid, active, superadmin )
       VALUES ( uuid_short(), NEW.id, 1, 0 );
    ELSEIF (NEW.admin = 0) THEN
        IF (@s = 0) THEN
            DELETE FROM admins WHERE userid = NEW.id and superadmin = 0;
        ELSEIF (@s = 1) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT='Cannot delete superadmin';
        END IF;
    END IF;
END;
