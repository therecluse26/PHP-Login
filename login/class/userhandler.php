<?php
/**
 * PHPLogin\UserHandler extends DbConn
 */
namespace PHPLogin;

/**
 * User handling functions
 *
 *  Various methods related to user management
 */
class UserHandler extends DbConn
{
    /**
    * Creates new user
    *
    * @param array $userarr Array of users
    *
    * @return mixed
    */
    public static function createUser(array $userarr)
    {
        try {
            $db = new DbConn;
            $default_role = RoleHandler::getDefaultRole();

            foreach ($userarr as $user) {

                // encrypt password
                $pw = PasswordHandler::encryptPw($user['pw']);

                // prepare sql and bind parameters
                $stmt = $db->conn->prepare("
                    INSERT INTO ".$db->tbl_members." (id, username, password, email) VALUES (:id, :username, :password, :email);
                    INSERT INTO ".$db->tbl_member_roles." (member_id, role_id) VALUES (:id, :role_id);
                ");
                $stmt->bindParam(':id', $user['id']);
                $stmt->bindParam(':username', $user['username']);
                $stmt->bindParam(':email', $user['email']);
                $stmt->bindParam(':password', $pw);
                $stmt->bindParam(':role_id', $default_role);
                $stmt->execute();
                unset($stmt);
            }
        } catch (\PDOException $e) {
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

    /**
    * Deletes user by `$userid`
    *
    * @param string $userid User ID
    *
    * @return mixed
    */
    public static function deleteUser(string $userid)
    {
        try {
            $ddb = new DbConn;
            $tbl_members = $ddb->tbl_members;
            $derr = '';

            $dstmt = $ddb->conn->prepare('delete from '.$tbl_members.' WHERE id = :uid');
            $dstmt->bindParam(':uid', $userid);
            $dstmt->execute();
        } catch (\PDOException $d) {
            $derr = 'Error: ' . $d->getMessage();
        }

        $resp = ($derr == '') ? true : $derr;

        return $resp;
    }

    /**
    * Verifies user
    *
    * @param array $userarr Array of User IDs
    * @param bool $verify   If user is verified
    *
    * @return array
    */
    public static function verifyUser(array $userarr, bool $verify)
    {
        try {
            $idset = [];

            foreach ($userarr as $user) {
                array_push($idset, $user['id']);
            }

            if (count($idset) > 0) {
                $in  = str_repeat('?,', count($idset) - 1) . '?';

                $vdb = new DbConn;
                $tbl_members = $vdb->tbl_members;

                // prepare sql and bind parameters
                $vstmt = $vdb->conn->prepare("UPDATE ".$tbl_members." SET verified = ".$verify." WHERE id in ($in)");
                $vstmt->execute($idset);

                $vresp['status'] = true;
                $vresp['message'] = '';
            } else {
                $vresp['status'] = false;
                $vresp['message'] = 'User(s) not found';
            }
        } catch (\PDOException $v) {
            $vresp['status'] = false;
            $vresp['message'] = 'Error: ' . $v->getMessage();
        }

        return $vresp;
    }

    /**
     * Bans user by `$user_id` for number of `$ban_hours`
     *
     * @param  string  $user_id   User ID
     * @param  integer $ban_hours Hours to ban user for
     * @param  [type]  $reason    Reason for banning user
     *
     * @return mixed
     */
    public static function banUser(string $user_id, float $ban_hours = 0, string $reason = null)
    {
        try {
            $db = new DbConn;
            $err = null;
            //$curr_timestamp = date("Y-m-d H:i:s");

            $stmt = $db->conn->prepare('
              UPDATE '.$db->tbl_members.' SET banned = 1 WHERE id = :user_id;
              INSERT INTO '.$db->tbl_member_jail.' (user_id, banned_hours, reason) VALUES (:user_id, :banned_hours, :reason);
            ');

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':banned_hours', $ban_hours);
            $stmt->bindParam(':reason', $reason);
            //$stmt->bindParam(':timestamp', $curr_timestamp);
            $stmt->execute();
        } catch (\PDOException $e) {
            $err = 'Error: ' . $e->getMessage();
        }

        $resp = ($err == null) ? true : $err;

        return $resp;
    }

    /**
     * Pulls user by email address
     *
     * @param  string $email Email address
     *
     * @return array
     */
    public static function pullUserByEmail(string $email)
    {
        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

        try {
            $sql = "SELECT id, email, username FROM ".$tbl_members." WHERE email = :email LIMIT 1";
            $stmt = $db->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Pulls user by ID
     *
     * @param  string $id User ID
     *
     * @return array
     */
    public static function pullUserById($id)
    {
        $db = new DbConn;
        $tbl_members = $db->tbl_members;
        $result = array();

        try {
            $sql = "SELECT id, email, username FROM ".$tbl_members." WHERE id = :id LIMIT 1";
            $stmt = $db->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }
}
