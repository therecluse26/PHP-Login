<?php
class NewUser extends DbConn
{
    public function createUser($userarr)
    {
        try {
            $tbl_members = $this->tbl_members;

            foreach($userarr as $user){

                // encrypt password
                $pw = PasswordCrypt::encryptPw($user['pw']);

                // prepare sql and bind parameters
                $stmt = $this->conn->prepare("INSERT INTO ".$tbl_members." (id, username, password, email) VALUES (:id, :username, :password, :email)");
                $stmt->bindParam(':id', $user['id']);
                $stmt->bindParam(':username', $user['username']);
                $stmt->bindParam(':email', $user['email']);
                $stmt->bindParam(':password', $pw);
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
