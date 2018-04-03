<?php


class NewUser extends DbConn
{
    public function createUser($userarr)
    {
        try {
            $default_role = RoleHandler::getDefaultRole();

            foreach ($userarr as $user) {

                // encrypt password
                $pw = PasswordCrypt::encryptPw($user['pw']);

                // prepare sql and bind parameters
                $stmt = $this->conn->prepare("
                    INSERT INTO ".$this->tbl_members." (id, username, password, email) VALUES (:id, :username, :password, :email);
                    INSERT INTO ".$this->tbl_member_roles." (member_id, role_id) VALUES (:id, :role_id);
                ");
                $stmt->bindParam(':id', $user['id']);
                $stmt->bindParam(':username', $user['username']);
                $stmt->bindParam(':email', $user['email']);
                $stmt->bindParam(':password', $pw);
                $stmt->bindParam(':role_id', $default_role);
                $stmt->execute();
                unset($stmt);
            }
        } catch (PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }
        //Determines returned value ('true' or error code)
        if (!isset($err)) {
            $resp = true;
        } else {
            $resp = $err;
        };

        return $resp;
    }
}
