<?php
/**
* AJAX page for user deletion in userverification.php
**/
require '../../vendor/autoload.php';
try {
    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $user = new PHPLogin\UserData;
    $role = new PHPLogin\RoleHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Delete Users'))) {
        $ids = $_POST['ids'];

        $eresult = $user->listSelectedUsers($ids);

        foreach ($eresult as $e) {
            try {
                $singleId = $e['id'];

                //Deletes user (unless superadmin)
                if ($role->checkRole($singleId, 'Superadmin')) {
                    header('HTTP/1.1 400 Bad Request');
                    throw new Exception("Cannot delete Superadmin");
                } else {
                    $dresponse = PHPLogin\UserHandler::deleteUser($singleId);
                }

                //Success
                if ($dresponse == 1) {
                    echo $dresponse;
                } else {
                    //Validation error from empty form variables
                    header('HTTP/1.1 400 Bad Request');
                    throw new Exception("Failure");
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
