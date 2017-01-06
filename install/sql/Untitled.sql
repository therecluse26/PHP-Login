DELIMITER $$ 

CREATE TRIGGER

 IF NOT EXISTS 
login4.add_admin 

AFTER INSERT ON login4.members FOR EACH ROW BEGIN
IF (NEW.admin = 1) THEN  INSERT INTO login4.admins (adminid, userid, active, superadmin ) 
VALUES (uuid_short(), NEW.id, 1, 0 ); END IF; END; GO;$$ DELIMITER ;