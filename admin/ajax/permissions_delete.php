<?php
/**
* AJAX page for role deletion in roles.php
**/
require '../../vendor/autoload.php';
try {
    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $permission = new PHPLogin\PermissionHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Delete Permissions'))) {
        $ids = json_decode($_POST['ids']);


        foreach ($ids as $id) {
            try {
                //Deletes role
                $permission_required = $permission->checkPermissionRequired($id);

                if ($permission_required['status']) {
                    header('HTTP/1.1 400 Bad Request');
                    throw new Exception("Cannot delete required permission");
                } else {
                    $dresponse = $permission->deletePermission($id);
                }

                //Success
                if ($dresponse['status'] == 1) {
                    echo $dresponse['status'];
                } else {
                    //Validation error from empty form variables
                    header('HTTP/1.1 400 Bad Request');
                    throw new Exception($dresponse['message']);
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
