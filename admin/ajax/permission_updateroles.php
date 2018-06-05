<?php
require '../../vendor/autoload.php';

try {
    session_start();

    $request = new PHPLogin\CSRFHandler;
    $auth = new PHPLogin\AuthorizationHandler;
    $permissionhandler = new PHPLogin\PermissionHandler;

    if ($request->valid_token() && ($auth->isSuperAdmin() || $auth->hasPermission('Assign Permissions to Roles'))) {
        try {
            $roles = json_decode($_POST['formData'], true);

            $permission_id = $_POST['permissionId'];

            $permissionhandler->updatePermissionRoles($roles, $permission_id);

            http_response_code(200);
            echo "true";
            return;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
