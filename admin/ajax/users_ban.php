<?php
/**
* AJAX page for user banning
**/
try {
    require '../../vendor/autoload.php';

    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $role = new PHPLogin\RoleHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Ban Users'))) {
        if (isset($_POST['uid']) && isset($_POST['ban_hours']) && isset($_POST['ban_reason'])) {
            $uid = $_POST['uid'];
            $ban_hours = $_POST['ban_hours'];
            $ban_reason = $_POST['ban_reason'];
        } else {
            throw new Exception("Missing data");
        }

        $eresult = PHPLogin\UserData::userDataPull($uid, 0);

        foreach ($eresult as $e) {
            $singleId = $e['id'];

            //Bans user
            //B//Deletes user (unless superadmin)
            if ($role->checkRole($singleId, 'Superadmin')) {
                header('HTTP/1.1 400 Bad Request');
                throw new Exception("Cannot ban Superadmin");
            } else {
                $dresponse = PHPLogin\UserHandler::banUser($singleId, $ban_hours, $ban_reason);
            }

            //Success
            if ($dresponse == 1) {
                echo $dresponse;
            } else {
                //Validation error from empty form variables
                //header('HTTP/1.1 400 Bad Request');
                throw new Exception("Failure");
            }
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
