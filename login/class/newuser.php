<?php
class NewUser extends DbConn
{
    public function createUser($userarr)
    {
        try {

            $db = new DbConn;
            $tbl_members = $db->tbl_members;

            foreach($userarr as $user){

                // encrypt password
                $pw = password_hash($user['pw'], PASSWORD_DEFAULT);

                // prepare sql and bind parameters
                $stmt = $db->conn->prepare("INSERT INTO ".$tbl_members." (id, username, password, email) VALUES (:id, :username, :password, :email)");
                $stmt->bindParam(':id', $user['id']);
                $stmt->bindParam(':username', $user['username']);
                $stmt->bindParam(':email', $user['email']);
                $stmt->bindParam(':password', $pw);
                $stmt->execute();
                unset($stmt);

            }

            $err = '';

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }
        //Determines returned value ('true' or error code)
        if ($err == '') {

            $success = 'true';

        } else {

            $success = $err;

        };

        return $success;

    }
}
