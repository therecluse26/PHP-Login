<?php
/**
* AJAX page for role deletion in roles.php
**/
require '../../vendor/autoload.php';
try {
    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $role = new PHPLogin\RoleHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Delete Roles'))) {
        $ids = $_POST['ids'];

        $eresult = $role->listSelectedRoles($ids);

        foreach ($eresult as $e) {
            try {
                $singleId = $e['id'];

                //Deletes role
                $role_name = $role->getRoleName($singleId);

                if (in_array($role_name, array('Superadmin', 'Admin', 'Standard User'))) {
                    header('HTTP/1.1 400 Bad Request');
                    throw new Exception("Cannot delete Superadmin, Admin or Standard User roles");
                } else {
                    $dresponse = $role->deleteRole($singleId);
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
